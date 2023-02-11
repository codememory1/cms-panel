<?php

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\LogInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\LogTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table('roles')]
#[ORM\HasLifecycleCallbacks]
class Role implements EntityInterface, LogInterface
{
    use IdentifierTrait;
    use TimestampTrait;
    use LogTrait;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: RolePermission::class, cascade: ['persist', 'remove'])]
    private Collection $permissions;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: User::class, cascade: ['remove'])]
    private Collection $users;

    public function __construct()
    {
        $this->generateUuid();

        $this->permissions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, RolePermission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function hasPermission(Permission $permission): bool
    {
        return $this
            ->getPermissions()
            ->exists(static fn (int $key, RolePermission $rolePermission) => $rolePermission->getPermission()->getKey() === $permission->getKey());
    }

    /**
     * @param array<int, Permission> $permissions
     */
    public function setPermissions(array $permissions): self
    {
        $this->permissions->clear();

        foreach ($permissions as $permission) {
            $rolePermission = new RolePermission();

            $rolePermission->setPermission($permission);

            $this->addPermission($rolePermission);
        }

        return $this;
    }

    public function addPermission(RolePermission $permission): self
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
            $permission->setRole($this);
        }

        return $this;
    }

    public function removePermission(RolePermission $permission): self
    {
        if ($this->permissions->removeElement($permission)) {
            // set the owning side to null (unless already changed)
            if ($permission->getRole() === $this) {
                $permission->setRole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }
}

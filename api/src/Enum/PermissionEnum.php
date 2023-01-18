<?php

namespace App\Enum;

enum PermissionEnum: string
{
    case ALL_PHONES = 'Просмотр всех телефонов';
    case CREATE_PHONE = 'Создание телефона';
    case UPDATE_PHONE = 'Редактирование телефона';
    case DELETE_PHONE = 'Удаление телефона';
    case ALL_ROLES = 'Просмотр всех ролей';
    case ALL_PERMISSIONS = 'Просмотр всех разрешений для ролей';
    case CREATE_ROLE = 'Создание роли';
    case UPDATE_ROLE = 'Редактирование роли';
    case DELETE_ROLE = 'Удаление роли';
    case ALL_USERS = 'Просмотр всех пользователей';
    case CREATE_USER = 'Создание пользователя';
    case UPDATE_USER = 'Обновление пользователя';
    case DELETE_USER = 'Удаление пользователя';
    case ADD_PERMISSION_TO_ROLE = 'Добавление разрешений к ролям';
    case DELETE_PERMISSION_TO_ROLE = 'Удаление разрешений у ролей';
}

import { Vue } from 'vue-property-decorator';
import { Store } from 'vuex';
import DeleteModal from '~/components/Modal/DeleteModal.vue';
import BaseTable from '~/components/Table/BaseTable.vue';
import CreateModal from '~/components/Modal/CreateModal.vue';
import UpdateModal from '~/components/Modal/UpdateModal.vue';
import ModalInterface from '~/interfaces/ModalInterface';

type InputDataType = { [key: string]: { error: boolean; value: any } };
type CollectedInputDataType = { data: { [key: string]: any }; validationIsOk: boolean };

export default class CrudService {
  private readonly app: Vue;
  private entities: Array<object> = [];
  private errors = {
    atAll: null,
    atDelete: null,
    atCreate: null,
    atEdit: null
  };

  private editedIndex: number = -1;

  public constructor(app: Vue) {
    this.app = app;
  }

  public getEntities(): Array<object> {
    return this.entities;
  }

  public getErrors(): object {
    return this.errors;
  }

  public getEditedIndex(): number {
    return this.editedIndex;
  }

  public getEditedEntity(): any {
    if (this.editedIndex === -1) {
      return undefined;
    }

    return this.entities[this.editedIndex];
  }

  public openEditModal(
    entity: object,
    input: InputDataType,
    vModelCallback: (entity: object) => void
  ): void {
    (this.app.$refs.editModal as DeleteModal).open();

    this.editedIndex = this.entities.indexOf(entity);

    vModelCallback(this.entities[this.editedIndex] as any);
  }

  public openDeleteModal(entity: object): void {
    (this.app.$refs.deleteModal as DeleteModal).open();

    this.editedIndex = this.entities.indexOf(entity);
  }

  public openEditableModal(entity: object, modal: ModalInterface): void {
    modal.open();

    this.editedIndex = this.entities.indexOf(entity);
  }

  public closeEditableModal(): void {
    this.editedIndex = -1;
  }

  public closeDeleteModal(): void {
    this.closeEditableModal();
  }

  public async allRequest(route: string, store: Store<any>, withTable: boolean = false) {
    if (withTable && this.app.$refs.table !== undefined) {
      (this.app.$refs.table as BaseTable).setIsLoading(true);
    }

    try {
      const response = await this.app.$api.get(route, {
        headers: {
          Authorization: `Bearer ${store.getters['modules/global-module/accessToken']}`
        }
      });

      this.entities = response.data.data;
    } catch (e) {
      this.errors.atAll = (e as any).response.data.error;
    } finally {
      if (withTable && this.app.$refs.table !== undefined) {
        (this.app.$refs.table as BaseTable).setIsLoading(false);
      }
    }
  }

  public createRequest(route: string, input: InputDataType): void {
    const collectedInputData = this.validatingAndCollectingInputData(input);
    const modal = this.app.$refs.createModal as CreateModal;

    if (collectedInputData.validationIsOk) {
      modal.setButtonIsLoading(true);

      this.app.$api
        .post(route, collectedInputData.data, {
          headers: {
            Authorization: `Bearer ${this.app.$store.getters['modules/global-module/accessToken']}`
          }
        })
        .then((response) => {
          this.entities.push(response.data.data);

          modal.close();
        })
        .catch((error) => {
          modal.setError(error.response.data.error);

          this.errors.atCreate = error.response.data.error;
        })
        .finally(() => {
          modal.setButtonIsLoading(false);
        });
    }
  }

  public editRequest(route: string, input: InputDataType, ref?: UpdateModal): void {
    const collectedInputData = this.validatingAndCollectingInputData(input);
    const modal = ref || (this.app.$refs.editModal as UpdateModal);

    if (collectedInputData.validationIsOk) {
      modal.setButtonIsLoading(true);

      this.app.$api
        .put(route, collectedInputData.data, {
          headers: {
            Authorization: `Bearer ${this.app.$store.getters['modules/global-module/accessToken']}`
          }
        })
        .then((response) => {
          const entity = response.data.data;

          this.entities.splice(this.editedIndex, 1);
          this.entities.push(entity);

          modal.close();
        })
        .catch((error) => {
          modal.setError(error.response.data.error);

          this.errors.atEdit = error.response.data.error;
        })
        .finally(() => {
          modal.setButtonIsLoading(false);
        });
    }
  }

  public deleteRequest(route: string): void {
    const modal = this.app.$refs.deleteModal as DeleteModal;

    modal.setButtonIsLoading(true);

    this.app.$api
      .delete(route, {
        headers: {
          Authorization: `Bearer ${this.app.$store.getters['modules/global-module/accessToken']}`
        }
      })
      .then(() => {
        this.entities.splice(this.editedIndex, 1);

        modal.close();
      })
      .catch((error) => {
        modal.setError(error.response.data.error);

        this.errors.atDelete = error.response.data.error;
      })
      .finally(() => {
        modal.setButtonIsLoading(false);
      });
  }

  private validatingAndCollectingInputData(input: InputDataType): CollectedInputDataType {
    const collectedData: { [key: string]: any } = {};
    let validationIsOk: boolean = true;

    const keys = Object.keys(input);

    keys.forEach((k) => {
      const inputData = input[k];

      if (inputData.value === null) {
        inputData.error = true;
        validationIsOk = false;
      }

      collectedData[k] = inputData.value;
    });

    return {
      data: collectedData,
      validationIsOk
    };
  }
}

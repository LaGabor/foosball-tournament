<template>
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit {{ editedThing }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cancel"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="editedData">Name:</label>
              <input type="text" class="form-control" :placeholder="data.name" id="editedData" v-model="dataName" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cancel">Close</button>
          <button type="button" class="btn btn-primary"   @click="saveChanges">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "EditModal",
  props: ["data","editedThing"],
  data() {
    return {
      dataName: this.data.name,
    };
  },
  methods: {
    saveChanges() {
      if(! (this.dataName.trim() === "")){
        this.$emit("updateData", this.dataName);
        this.dataName = '';
        const editModal = document.getElementById('editModal');
        editModal.classList.remove('show');
        const modalBackdrop = document.getElementsByClassName('modal-backdrop')[0];
        modalBackdrop.parentNode.removeChild(modalBackdrop);
      }
    },
    cancel(){
      this.dataName = '';
    }
  }
}
</script>

<style scoped>

</style>

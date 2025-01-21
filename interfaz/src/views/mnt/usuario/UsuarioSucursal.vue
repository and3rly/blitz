<template>
  <form action="">
    <div class="row mb-1">
      <label 
        for="inputNombre" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Nombre: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputNombre"
          required
        >
      </div>
    </div>
  </form>
</template>

<script>
  export default {

  }
</script>
<template>
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">
      <i class="fa fa-boxes-stacked me-2"></i>Producto
    </h3>
    <div class="ms-auto">
      <button class="btn btn-theme">
        <i class="fa fa-plus-circle fa-fw me-1"></i> Nuevo
      </button>
    </div>
  </div>

  <template v-if="inicio">
    <div class="text-center">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <br>
      Cargando...
    </div>
  </template>

  
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>#</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
  
</template>

<script>
  export default {
    data: () => ({
      inicio: false
    })
  }
</script>
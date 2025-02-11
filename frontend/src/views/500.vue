<template>
    <div class="error-page text-center">
      <h1 class="display-1 fw-bold text-warning">500</h1>
      <p class="lead">Something went wrong on our end. Please try again later.</p>
      <button @click="reloadPage" class="btn btn-secondary mt-3">
        Try Again
      </button>
    </div>
  </template>
  
  <script>
  export default {
    name: "ServerErrorPage",
    methods: {
      reloadPage() {
        location.reload();
      },
    },
  };
  </script>
  
  <style scoped>
  .error-page {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #fff3cd;
  }
  </style>
  
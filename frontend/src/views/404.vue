<template>
    <div class="error-page text-center">
      <h1 class="display-1 fw-bold text-danger">404</h1>
      <p class="lead">Oops! The page you're looking for doesn't exist.</p>
      <router-link to="/" class="btn btn-primary mt-3">
        Go Home
      </router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: "NotFoundPage",
  };
  </script>
  
  <style scoped>
  .error-page {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f8f9fa;
  }
  </style>
  
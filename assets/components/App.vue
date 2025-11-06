<template>
  <div class="app-root">
    <h1>{{ message }}</h1>
    <button @click="count++">Кликнули {{ count }} раз</button>
  </div>
</template>

<script>
export default {
  data() {
    return { message: 'Привет из Vue!', count: 0 }
  }
}
</script>

<style lang="scss">
.app-root {
  padding: 2rem;
  h1 { color: #42b983; }
  button { margin-top: 1rem; padding: .5rem 1rem; border-radius: 6px; }
}
</style>
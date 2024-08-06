<script setup>
</script>
<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-200 background-image poppins-medium">
        <slot />
    </div>
</template>
<style lang="scss" scoped>
/* En un archivo CSS */
.background-image {
  background-image: url('/background/vecteezy_white.jpg');
  background-size: cover;
  background-position: center;
}
</style>
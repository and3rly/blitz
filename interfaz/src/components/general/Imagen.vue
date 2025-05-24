<template>
  <img 
    :src="urlImg"
    :width="width" 
  />
</template>

<script>
export default {
  props: {
    img: {
      type: [String, File],
      default: null
    },
    width: {
      type: Number,
      default: 200
    }
  },
  data() {
    return {
      urlImg: "/assets/imagen/sinimg.png"
    };
  },
  watch: {
    img: {
      immediate: true,
      handler(v) {
        if (v instanceof File) {
          this.urlImg = URL.createObjectURL(v);
        } else if (typeof v === 'string' && v.trim() !== '') {
          this.urlImg = "https://lh3.googleusercontent.com/d/" + v
        } else {
          this.urlImg = "/assets/imagen/sinimg.png"
        }
      }
    }
  }
}
</script>
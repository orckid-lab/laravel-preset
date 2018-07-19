<script>
  import Vue from 'vue';
  import Flickity from 'flickity-bg-lazyload';
  import FlickComponent from 'vue-flickity';

  const FlickSlider = Vue.extend(FlickComponent);

  export default FlickSlider.extend({
    name: 'flick-slider',
    props: {
      config: {
        type: String,
        default: 'default'
      }
    },
    data() {
      return {
        configs: {
          default: {
            prevNextButtons: false,
            pageDots: true,
            autoPlay: 8000,
            draggable: true,
            adaptiveHeight: false
          },
          fullscreen: {
            pauseAutoPlayOnHover: false,
            prevNextButtons: true,
            pageDots: false,
            autoPlay: 8000,
            draggable: true,
            bgLazyLoad: 2,
            wrapAround: false
          },
          itemSlider: {
            prevNextButtons: true,
            pageDots: false,
            draggable: true,
            contain: true,
            resize: true,
            adaptiveHeight: true,
            groupCells: '100%'
          },
          single: {
            pauseAutoPlayOnHover: false,
            prevNextButtons: false,
            pageDots: false,
            autoPlay: false,
            draggable: false,
            bgLazyLoad: 2,
            wrapAround: false
          },
          gallery: {
            pauseAutoPlayOnHover: false,
            prevNextButtons: true,
            pageDots: false,
            autoPlay: 8000,
            draggable: true,
            bgLazyLoad: 2,
            wrapAround: false
          }
        }
      };
    },
    methods: {
      init() {
        this.flickity = new Flickity(this.$el, this.configs[this.config]);
        this.$emit('init', this.flickity);
      }
    }
  });
</script>

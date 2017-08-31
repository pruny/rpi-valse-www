var app = new Vue({
  el: '#app',
  data: {
    items: [],
    interval: null,
    wait_for_data_count: 0
  },
  methods: {
    btn_press: function (item) {
      if(item.mode == "IN") return false;

      item.value  = (item.value == 0) ? 1 : 0;
      if(item.mode == "MIXT") item.value = 1;

      const that = this;
      that.wait_for_data_count += 1;

      axios.get("api/write/" + item.pin + "/" + item.value)
      .then(res => {
        setTimeout(function() { that.wait_for_data_count -= 1; }, 200);
        // to avoid visual flip-flop effect when pushing the button
        // the timeout must be more that double of the init interval (500 in this case)
        //setTimeout(function() { that.wait_for_data_count -= 1; }, 1234);
      });
    },

    loadData: function () {
      const that = this;
      axios.get("api/read")
      .then(res => {
        if(that.wait_for_data_count != 0) return false;
        that.items = res.data;
      });
    }
  },

    mounted: function () {
      axios.get("api/init");
      this.loadData();
      this.interval = setInterval(function () {
        this.loadData();
      }.bind(this), 500);
    },

   beforeDestroy: function(){ clearInterval(this.interval); }
});

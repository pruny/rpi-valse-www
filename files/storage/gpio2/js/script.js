$( document ).ready(function() {
  api_init();
  update_page();
  setInterval(update_page, 2000);
  $("#container").on('click', '[id^=button_]', btn_press);
});

var api_array = new Array();
var sending_data = false;

function update_page() {
  if(sending_data) return false;
  api_read(function(result, textStatus) {
    if(JSON.stringify(result) != JSON.stringify(api_array)) {
      html_btn_data = "";
      last_category = "";
      $.each(result, function(i, item) {
         if(item["category"] != last_category) {
           html_btn_data += "<h1>"+item["category"]+"</h1>";
           last_category = item["category"];
         }
         html_btn_data += get_btn_html(item);
       });
       $( "#container" ).empty().append(html_btn_data);
    }
    api_array = result;
  });
}


function get_btn_html(btn) {
  var btn_value = btn["value"];
  var btn_ref_value = btn["ref_value"];
  var btn_class = (btn_value != btn_ref_value) ? "btn_on" : "btn_off";
  var btn_id = "button_" + btn["pin"];
  var btn_icon = btn["icon"];
  var btn_label = btn["label"];
  var btn_mode = btn["mode"];

  if(btn_mode == "MIXT") btn_id += "_MIXT";

  return "<div class='btn_div "+btn_class+"'> \
      <a mode="+btn_mode+" ref_value="+btn_ref_value+" value="+btn_value+" id='"+btn_id+"'>"+btn_icon+"</a> \
      <span class=btn_light></span> \
      <h4>"+btn_label+"</h4> \
      </div>";
}

function btn_press() {
  if(mode == "IN") return false;

  var pin = ($(this).attr('id')).split("_")[1];
  var mode = $(this).attr('mode');
  var value = $(this).attr('value');
  var ref_value = $(this).attr('ref_value');

  if(mode == "MIXT") {
    api_write(pin, "1");
    $(this).parent().removeClass("btn_off").addClass("btn_on");
    api_array = new Array();
  }
  else if (mode == "OUT") {
    new_pin_val = (value == "0") ? "1" : "0";
    api_write(pin, new_pin_val);

    var btn_class = (value != ref_value) ? "btn_on" : "btn_off";
    var btn_class_new = (value != ref_value) ? "btn_off" : "btn_on";

    $(this).parent().removeClass(btn_class).addClass(btn_class_new);
    $(this).attr("value", new_pin_val);
  }
}

// API CALLS

function api_read(callback) {
  $.ajax({
     type: "GET",
     url:'api/read',
     success: function(result, textStatus) { callback(result, textStatus) }
  });
}

function api_write(pin, value) {
  sending_data = true;
  $.ajax({
     type: "GET",
     url:'api/write/'+pin+"/"+value,
     success: function(result, textStatus) { sending_data = false; }
  });
}

function api_init() {
  $.ajax({
     type: "GET",
     url:'api/init',
     success: function(result, textStatus) {}
  });
}

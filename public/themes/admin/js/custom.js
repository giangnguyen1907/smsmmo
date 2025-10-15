


function openPopupImg(id) {

  CKFinder.popup( {

    chooseFiles: true,

    onInit: function( finder ) {

      finder.on( 'files:choose', function( evt ) {

        var file = evt.data.files.first();

        var src = file.getUrl();

        $('#'+id).val(src);

        $('#view_'+id).attr('src',src);

      } );

      finder.on( 'file:choose:resizedImage', function( evt ) {

        document.getElementById( "view_"+id ).value = evt.data.resizedUrl;

      } );

    }

  } );

}





function getUrlPart(title,alias){

	var str = (document.getElementById(title).value);

	str= str.toLowerCase();

	str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");

	str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");

	str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");

	str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");

	str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");

	str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");

	str= str.replace(/đ/g,"d");

  str= str.replace(/'|"/g,"");

	str= str.replace(/!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'| |\"|\&|\#|\[|\]|~/g,"-");

	str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-

	str= str.replace(/^\-+|\-+$/g,"");//cắt bỏ ký tự - ở đầu và cuối chuỗi

	document.getElementById(alias).value = str;

	return str;

}




function demKytu(id_input,id_show){

  var length_input = $( "#"+id_input ).val().length;

  var max_length = $( "#"+id_input ).attr('maxLength');

  //alert('AASSS'+length_input);

  $( "#"+id_show ).html( length_input + '/' + max_length );

}



// Custom laravel-filemanager

// Prefix route



var route_prefix = "/admin/filemanager";

// config CKEDITOR

var ck_options = {

  language: 'en',

  uiColor: '#E0F2F4',

  height: 450,

  entities: false,

  fullPage: false,

  // allow style and css

  allowedContent: true,

  // auto wrap content in p tag

  autoParagraph: false,

  // Config file & image

  filebrowserImageBrowseUrl: route_prefix + '?type=other',

  filebrowserImageUploadUrl: route_prefix + '/upload?type=file&_token=',

  filebrowserBrowseUrl: route_prefix + '?type=file',

  filebrowserUploadUrl: route_prefix + '/upload?type=file&_token=',
  
  versionCheck: false

};



// Use single input

(function($) {

  $.fn.filemanager = function(type, options) {

    type = type || 'file';



    this.on('click', function(e) {

      var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';

      var target_input = $('#' + $(this).data('input'));

      var target_preview = $('#' + $(this).data('preview'));

      // ThangNH add config to get type

      var _type = $(this).data('type');

      type = _type || type;

      // End ThangNH

      var w = 1200,

        h = 750;

      var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;

      var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

      width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;

      height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;



      var left = ((width / 2) - (w / 2)) + dualScreenLeft;

      var top = ((height / 2) - (h / 2)) + dualScreenTop;



      window.open(route_prefix + '?type=' + type, 'FileManager', 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);



      window.SetUrl = function(items) {



        var file_path = items.map(function(item) {

          return item.url;

        }).join(',');



        // set the value of the desired input to image url

        target_input.val('').val(file_path).trigger('change');



        // clear previous preview

        target_preview.html('');



        // set or change the preview image src

        items.forEach(function(item) {

          target_preview.append(

            $('<img>').css('height', '5rem').attr('src', item.thumb_url)

          );

        });



        // trigger change event

        target_preview.trigger('change');

      };

      return false;

    });

  }

})(jQuery);






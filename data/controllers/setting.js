languageInfo=languageInfo();
$(document).ready(function () {
      $("#SYSCompanyLogo").on("change", function () {
            $("#imageUploadFormPic").submit();
      });
      $('#imageUploadFormPic').on('submit', (function (e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","updateProfileImage");
            formData.append("SYSID_UIZP",1);
            $.ajax({
                  url: 'models/_setting.php',
                  type: 'POST',
                  cache: false,
                  contentType: false,
                  processData: false,
                  data: formData,
                  dataType: 'json',
                  complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                  },
                  beforeSend: function () {
                      oneOpenLoader("#"+$(this).parent().id,"self","dark");
                  },
                  success: function (response) {
                        if (response.is_success == true) {
                              oneMessege("",response.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                              location.reload();
                        } else {
                              oneMessege("",response.data,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }
                  },
                  complete: function (data) {
                  }
            });
      }));
      $('#company_info_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","updateCompanyInfo");
            formData.append("SYSID_UIZP",1);
            $.ajax({
                  url: 'models/_setting.php',
                  type: 'POST',
                  cache: false,
                  contentType: false,
                  processData: false,
                  data: formData,
                  dataType: 'json',
                  complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                  },
                  beforeSend: function () {
                      oneOpenLoader("#"+$(this).parent().id,"self","dark");
                  },
                  success: function (response) {
                        if (response.is_success == true) {
                              oneMessege("",response.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                             // location.reload();
                        } else {
                              oneMessege("",response.data,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }
                  },
                  complete: function (data) {
                  }
            });
      });
      $('#system_info_form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","updateSystemInfo");
            formData.append("SYSID_UIZP",1);
            $.ajax({
                  url: 'models/_setting.php',
                  type: 'POST',
                  cache: false,
                  contentType: false,
                  processData: false,
                  data: formData,
                  dataType: 'json',
                  complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                  },
                  beforeSend: function () {
                      oneOpenLoader("#"+$(this).parent().id,"self","dark");
                  },
                  success: function (response) {
                        if (response.is_success == true) {
                              oneMessege("",response.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                              location.reload();
                        } else {
                              oneMessege("",response.data,"danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        }
                  },
                  complete: function (data) {
                  }
            });
      });
      $("#SYSTimezone_USZ").select2({
            placeholder: "Select Time Zone",
      });
      $('.dateStyle').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleDatePicker: true
        });
      $("#SYSTimezone_USZ").val($("#SystemTimeZone").val()).trigger("change"); 
});
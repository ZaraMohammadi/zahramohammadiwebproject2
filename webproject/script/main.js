$(document).ready(function(){
    $('.timePicker').persianDatepicker({
        "inline": false,
        "format": "LLLL",
        "viewMode": "day",
        "initialValue": true,
        "minDate": null,
        "maxDate": null,
        "autoClose": true,
        "position": "auto",
        "altFormat": "lll",
        "altField": "#altfieldExample",
        "onlyTimePicker": false,
        "onlySelectOnDate": false,
        "calendarType": "persian",
        "inputDelay": 800,
        "observer": true,
        "calendar": {
          "persian": {
            "locale": "fa",
            "showHint": false,
            "leapYearMode": "algorithmic"
          },
          "gregorian": {
            "locale": "en",
            "showHint": false
          }
        },
        "navigator": {
          "enabled": true,
          "scroll": {
            "enabled": true
          },
          "text": {
            "btnNextText": "<",
            "btnPrevText": ">"
          }
        },
        "toolbox": {
          "enabled": true,
          "calendarSwitch": {
            "enabled": true,
            "format": "MMMM"
          },
          "todayButton": {
            "enabled": true,
            "text": {
              "fa": "امروز",
              "en": "Today"
            }
          },
          "submitButton": {
            "enabled": true,
            "text": {
              "fa": "تایید",
              "en": "Submit"
            }
          },
          "text": {
            "btnToday": "امروز"
          }
        },
        "timePicker": {
          "enabled": true,
          "step": 1,
          "hour": {
            "enabled": true,
            "step": null
          },
          "minute": {
            "enabled": true,
            "step": null
          },
          "second": {
            "enabled": false,
            "step": null
          },
          "meridian": {
            "enabled": false
          }
        },
        "dayPicker": {
          "enabled": true,
          "titleFormat": "YYYY MMMM"
        },
        "monthPicker": {
          "enabled": true,
          "titleFormat": "YYYY"
        },
        "yearPicker": {
          "enabled": true,
          "titleFormat": "YYYY"
        }
      });
    


      /////////*modals functions*////////
      $('#d-alert-start').on('show.bs.modal', function (e) {
        $('#duty-show0-Modal').hide();
        setTimeout(function(){
            location.reload();},1500);
      });

      $('#duty-modal-compelet').on('show.bs.modal', function (e) {

        $('#duty-show1-Modal').hide();
        
      });
      $('#duty-modal-compelet button.close').click(function(){
        location.reload();
      });

      $('#duty-modal-report').on('show.bs.modal', function (e) {

        $('#duty-show2-Modal').hide();
       
      });

      $('#duty-modal-report button').click(function(){
        location.reload();
      });
      


      /*****functions for page admins session*******/
      $('#yes-del-session').click(function(){
        $('#ASession-info-Modal').hide();
        $('#ASession-delete-Modal').hide();
        setTimeout(function(){
            location.reload();},1500);
      });


      $('.btn-session-delM').click(function(){
        ///location.reload();
      });

      $('#Sduty-modal-compelet').on('show.bs.modal', function (e) {

        $('#Sduty-show1-Modal').hide();
        
      });
      $('#Sduty-modal-compelet button.close').click(function(){
        location.reload();
      });

      $('#Sduty-modal-report').on('show.bs.modal', function (e) {

        $('#Sduty-show2-Modal').hide();
       
      });

      $('#Sduty-modal-report button').click(function(){
        location.reload();
      });

      $('#m-s-btnaddsessionmember').click(function(){
        $('#addM-session-Modal').hide();
    
        setTimeout(function(){
            location.reload();},1500);
      });

      $('#yes-del-duty').click(function(){
        $('#Sduty-delete-Modal').hide();
        $('#Sduty-show0-Modal').hide();
        setTimeout(function(){
            location.reload();},1500);
      })


      //login functions
      $("#login-form").submit(function(){
        event.preventDefault();

        //alert("diiiiiii");
        var username=$("#login-username").val();
        var password=$("#login-pass").val();

       $.ajax({

            type: "POST",
            url: "http://localhost/php_functions.php",
            data: {username: username, password: password},

            success: function(response){

                alert(response);
                //var resp = JSON.parse(response);

                /*if (resp.success == "1"){
                    alert("عضویت با موفقیت انجام شد")
                } else {
                    alert(resp.message);
                }*/
            }
        });
      });
});
<script>
    // @if(env('APP_ENV') == 'production')
    // document.addEventListener('contextmenu', event => event.preventDefault());
    // document.onkeydown = function(e) {
    //     if (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 117) || e.keyCode === 123) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // };
    // @endif

    @if($errors->any())
        cuteAlert({
            type: "error",
            title: '',
            message: "{!! implode('', $errors->all('<div class=\'w-100\'><div class=\'am-alert-text\'>:message</div></div>')) !!}",
            buttonText: '@lang("Ok")'
        })
    @endif

    @if(session('success'))
      cuteToast({
          type: "success",
          title: "",
          message: "{{session('success')}}",
          timer: 5000
      })
    @endif
    @if(session('error'))
      cuteToast({
          type: "error",
          title: "",
          message: "{{session('error')}}",
          timer: 5000
      })
    @endif
    @if(session('info'))
      cuteToast({
          type: "info",
          title: "",
          message: "{{session('info')}}",
          timer: 5000
      })
    @endif
    @if(session('warning'))
      cuteToast({
          type: "warning",
          title: "",
          message: "{{session('warning')}}",
          timer: 5000
      })
    @endif
</script>

{{-- cuteToast({
    type: "success",
    message: "Info Message",
    timer: 5000
})
cuteAlert({
    type: "question",
    title: "Confirm Title",
    message: "Confirm Message",
    confirmText: "Okay",
    cancelText: "Cancel"
}).then((e)=>{
    if ( e == ("Thanks")){

    } else {
        alert(":-(");
    }
})
cuteAlert({
    type: "info",
    title: "Info Title",
    message: "Info Message",
    buttonText: "Okay"
})
cuteAlert({
    type: "warning",
    title: "Warning Title",
    message: "Warning Message",
    buttonText: "Okay"
})
cuteAlert({
    type: "error",
    title: "Error Title",
    message: "Error Message",
    buttonText: "Okay"
})
cuteAlert({
    type: "success",
    title: "Success Title",
    message: "Success Message",
    buttonText: "Okay"
}) --}}

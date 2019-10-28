document.addEventListener("DOMContentLoaded", function(e) {
    var showAuthBtn = document.getElementById("getweb-show-auth-form"),
        authContainer = document.getElementById("getweb-auth-container"),
        close = document.getElementById("getweb-auth-close"),
        authForm = document.getElementById('getweb-auth-form'),
        status = authForm.querySelector('[data-message="status"]');

        showAuthBtn.addEventListener("click", function() {
            authContainer.classList.add("show"), 
            showAuthBtn.parentElement.classList.add("hide")
    }),
     close.addEventListener("click", function() {
        authContainer.classList.remove("show"), 
        showAuthBtn.parentElement.classList.remove("hide")
    });

    authForm.addEventListener('submit', function(e) {
        e.preventDefault();

        //reset the form message
        resetMessages();

        //collect all the data
        var data = {
            name: authForm.querySelector('[name="username"]').value,
            password: authForm.querySelector('[name="password"]').value,
            nonce : authForm.querySelector('[name="getweb_auth"]').value
        }

        //valid everything
        if( !data.name || !data.password ){
            status.innerHTML = "Missing Data";
            status.classList.add('error');
            return;
        }

        //ajax http host request

        //var url = 'http://localhost/plugins/wp-admin/admin-ajax.php';
        var url = authForm.dataset.url;
        var params = new URLSearchParams(new FormData(authForm));

        authForm.querySelector('[name="submit"]').value = "Logging in...";
        authForm.querySelector('[name="submit"]').disabled = true;

        fetch(url,{
            method: "POST",
            body: params
        })
        .then(res => res.json())
            .catch(error => {
                resetMessages();
            })
        .then(response => {
            resetMessages();
             
            if(response == 0 || !response.status){
                status.innerHTML = response.message;
                status.classList.add('error');
                return;
            }
            status.innerHTML = response.message;
            status.classList.add('success');
            authForm.reset();
            window.location.reload();
            
            
        })
    });

    function resetMessages(){
        status.innerHTML = "";
        status.classList.remove("succes", "error");
        authForm.querySelector('[name="submit"]').value = "Login";
        authForm.querySelector('[name="submit"]').disabled = false;
        
    }

    
});

//# sourceMappingURL=auth.js.map

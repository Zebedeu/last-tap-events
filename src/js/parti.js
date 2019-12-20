document.addEventListener('DOMContentLoaded', function (e) {
    let participantForm = document.getElementById('event-participant-form');

    participantForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // reset the form messages
        resetMessages();

        // collect all the data
        let data = {

            lastTap_user_id: participantForm.querySelector('[name="lastTap_user_id"]').value,
            post_event_id: participantForm.querySelector('[name="post_event_id"]').value,
            name: participantForm.querySelector('[name="name"]').value,
            email: participantForm.querySelector('[name="email"]').value,
            telephone: participantForm.querySelector('[name="telephone"]').value,
            message: participantForm.querySelector('[name="message"]').value,
            party: participantForm.querySelector('#party'),
            nonce: participantForm.querySelector('[name="nonce"]').value
        }

        // validate everything
        if (!data.lastTap_user_id) {
            return;
        }
     if (!data.post_event_id) {
            return;
        }
     if (!data.name) {
            testimonialForm.querySelector('[data-error="invalidName"]').classList.add('show');
            return;
        }

        if (!validateEmail(data.email)) {
            participantForm.querySelector('[data-error="invalidEmail"]').classList.add('show');
            return;
        }

        if (!data.telephone) {
            participantForm.querySelector('[data-error="invalidTelephone"]').classList.add('show');
            return;
        }
        if (!data.message) {
            participantForm.querySelector('[data-error="invalidMessage"]').classList.add('show');
            return;
        }
        if (!data.party.checked)  {
            participantForm.querySelector('[data-error="invalidChecked"]').classList.add('show');
            return;
        }

        // ajax http post request
        let url = participantForm.dataset.url;
        let params = new URLSearchParams(new FormData(participantForm));

        participantForm.querySelector('.js-form-submission').classList.add('show');

        fetch(url, {
            method: "POST",
            body: params
        }).then(res => res.json())
            .catch(error => {
                resetMessages();
                participantForm.querySelector('.js-form-error').classList.add('show');
            })
            .then(response => {
                resetMessages();

                if (response === 0 || response.status === 'error') {
                    participantForm.querySelector('.js-form-error').classList.add('show');
                    return;
                }

                participantForm.querySelector('.js-form-success').classList.add('show');
                participantForm.querySelector('.js-form-status').classList.add('show');
                participantForm.reset();
            })
    });
});

function resetMessages() {
    document.querySelectorAll('.field-msg').forEach(f => f.classList.remove('show'));
}

function validateEmail(email) {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validateCheckBox (argument) {
    alert(argument);
}

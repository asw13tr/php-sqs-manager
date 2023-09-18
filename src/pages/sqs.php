
<form id="qForm" class="p-3">
    <div class="row">
        <div class="col-8">
            <div class="input-group">
                <input name="queueMessage" autofocus="on" type="text" class="form-control" placeholder="Nachricht" id="message" />
                <button class="btn btn-primary">Senden Nachricht</button>
            </div>
        </div>
    </div>
</form>
<hr>
<button onclick="getMessage()" class="btn btn-info">Nachrichten abrufen</button>
<table id="resultTable" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>Message</th>
        <th>Job</th>
        <th>Benutzer</th>
        <th>query</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    const objForm = document.querySelector("form#qForm");
    objForm.onsubmit = function(e){
        e.preventDefault();
        let objMessage = e.target.querySelector("input#message");

        axios.post("/aws/sender.php", {
            "message": objMessage.value
        })
            .catch(error => {
                console.log("Etwas ist schief gelaufen : ", error)
            })
            .then(response => {
                objMessage.value = ""
                console.log(response.data)
                if(response.data.status===true){
                    Toast.fire({
                        icon: 'success',
                        title: `Ihre Aufgabe wurde in die Warteschlange gestellt. Wir werden Sie benachrichtigen, wenn der Vorgang abgeschlossen ist. <br>MessageId: ${response.data.id}. `
                    })
                }
            });
    }








    const resultArea = document.querySelector("table#resultTable tbody");
    const getMessage = function(){
        resultArea.innerHTML = "";

        axios.get("/aws/receiver.php", {})
            .catch(error => {
                console.log("Etwas ist schief gelaufen : ", error)
            })
            .then(response => {
                if(response.data.status && response.data.messages){
                    response.data.messages.forEach(msg => {
                        let tr = `<tr>
                                            <td>${msg.id}</td>
                                            <td>${msg.body.text}</td>
                                            <td>${msg.body.job}</td>
                                            <td>${msg.body.benutzer}</td>
                                            <td>${msg.body.query}</td>
                                    </tr>`;
                        resultArea.innerHTML += tr
                    })
                }
            });
    }
</script>

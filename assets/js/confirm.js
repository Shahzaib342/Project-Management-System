function confirmAction(action){
    if(action === "delete"){
        var message = "Are you sure you want to delete this record?";
    }
    confirm(message);
}
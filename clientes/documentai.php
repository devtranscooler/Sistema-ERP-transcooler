<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <button onclick="procesar()" >Hola</button>
</body>


<script>
function procesar() {
    fetch('procesar.php')
        .then(res => res.json())
        .then(response => {
            console.log(response);
        });
}
</script>
</html>
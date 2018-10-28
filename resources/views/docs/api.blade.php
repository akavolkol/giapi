<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>API Docs</title>
  <link rel="stylesheet" href="//unpkg.com/swagger-ui-dist@3/swagger-ui.css"/>
</head>
<body>
<div id="swagger-ui"></div>
<script src="//unpkg.com/swagger-ui-dist@3/swagger-ui-bundle.js"></script>
<script>
  SwaggerUIBundle({
      url: "/api.json",
      dom_id: '#swagger-ui'
  });
</script>
</body>
</html>

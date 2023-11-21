<?php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
    'githubAuth' => config('services.github.client_id'),
];
$appJs = mix('dist/js/app.js');
$appCss = mix('dist/css/app.css');
?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
  <meta charset="utf-8">
  

  <title><?php echo e(config('app.name')); ?></title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

  <link rel="stylesheet" href="<?php echo e((str_starts_with($appCss, '//') ? 'http:' : '').$appCss); ?>">
</head>
<body>
  <div id="app">
  </div>

  <script>
    window.config = <?php echo json_encode($config, 15, 512) ?>;
  </script>

  
  
  <script src="<?php echo e((str_starts_with($appJs, '//') ? 'http:' : '').$appJs); ?>"></script>
</body>
</html>
<?php /**PATH C:\OSPanel\domains\laravel-vue-spa\resources\views/spa.blade.php ENDPATH**/ ?>
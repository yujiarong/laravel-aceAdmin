# laravel_aceAdmin
laravel  aceAdmin  php后台管理框架

# 介绍

      1. Larave5.5,AceAdmin后台管理模板(http://ace.jeka.by),php >=7.0.0
      2. barryvdh/laravel-debugbar: ^3.1  调试器
      3. maatwebsite/excel:^2.1  excel,csv,pdf等文件处理
      4. php-amqplib/php-amqplib: ^2.7  rabbitmq队列处理,只需要配置一下参数 ,使用方法在help.php中已经写好.
      5. yajra/laravel-datatables-oracle  lavel datatables 集成使用很方便.


# 安装步骤
1. git clone 
2. composer install
3. cp .env.example .env 复制配置文件
4. php artisan key:generate 创建新的应用程序密钥
5. 编辑 .env 文件配置数据库

      DB_HOST=YOUR_DATABASE_HOST
      
      DB_DATABASE=YOUR_DATABASE_NAME
      
      DB_USERNAME=YOUR_DATABASE_USERNAME
      
      DB_PASSWORD=YOUR_DATABASE_PASSWORD
6. php artisan migrate  数据库迁移
7. php artisan db:seed  数据库填充
8. windows可以直接使用 php artisan serve  http://localhost:8000访问, 使用nginx配置需要注意 try_files $uri $uri/ /index.php?$query_string;

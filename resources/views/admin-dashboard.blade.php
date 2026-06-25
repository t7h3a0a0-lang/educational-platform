<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, initial-scale=1.0">
    <title>لوحة التحكم | أدمن مدونة لارافيل</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body.admin-body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            right: 0;
            top: 0;
            z-index: 100;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .sidebar-nav ul {
            list-style: none;
            margin-top: 2rem;
        }

        .sidebar-nav ul li a {
            display: block;
            padding: 12px 24px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .sidebar-nav ul li a:hover,
        .sidebar-nav ul li a.active {
            background: rgba(255, 255, 255, 0.2);
            border-right: 4px solid white;
        }

        /* Admin Main */
        .admin-main {
            flex: 1;
            margin-right: 280px;
            width: calc(100% - 280px);
        }

        /* Admin Navbar */
        .admin-navbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .navbar-search input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            width: 250px;
            font-size: 0.9rem;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Admin Content */
        .admin-content {
            padding: 2rem;
        }

        .admin-page-title {
            margin-bottom: 2rem;
            color: #333;
            font-size: 1.8rem;
        }

        /* Admin Table */
        .admin-table-wrapper {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .admin-table thead {
            background: #667eea;
            color: white;
        }

        .admin-table th,
        .admin-table td {
            padding: 1rem;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        .admin-table tbody tr:hover {
            background: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-edit,
        .btn-delete {
            padding: 5px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #4caf50;
            color: white;
        }

        .btn-edit:hover {
            background: #45a049;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #da190b;
        }

        /* Admin Add Post Form */
        .admin-add-post {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .admin-add-post h2 {
            margin-bottom: 1.5rem;
            color: #667eea;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 250px;
            }
            .admin-main {
                margin-right: 250px;
                width: calc(100% - 250px);
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
                width: 280px;
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-right: 0;
                width: 100%;
            }
            
            .admin-navbar {
                padding: 1rem;
            }
            
            .navbar-search input {
                width: 180px;
            }
            
            .admin-content {
                padding: 1rem;
            }
            
            .admin-page-title {
                font-size: 1.5rem;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn-edit,
            .btn-delete {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .navbar-search input {
                width: 120px;
            }
            
            .navbar-user span {
                display: none;
            }
            
            .admin-add-post {
                padding: 1rem;
            }
            
            .admin-table th,
            .admin-table td {
                padding: 0.75rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>📘 مدونة لارافيل</h2>
                <p>لوحة التحكم</p>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#" class="active">📊 لوحة المعلومات</a></li>
                    <li><a href="#">📝 المنشورات</a></li>
                    <li><a href="add-post.html">➕ إضافة منشور</a></li>
                    <li><a href="#">🏷️ التصنيفات</a></li>
                    <li><a href="#">👥 المستخدمين</a></li>
                    <li><a href="#">⚙️ الإعدادات</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Navbar -->
            <nav class="admin-navbar">
                <div class="navbar-search">
                    <input type="text" placeholder="بحث...">
                </div>
                <div class="navbar-user">
                    <span>مرحباً، أحمد</span>
                    <div class="user-avatar">أ</div>
                </div>
            </nav>

            <!-- Content -->
            <div class="admin-content">
                <h1 class="admin-page-title">المنشورات</h1>
                
                <!-- جدول عرض المنشورات -->
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>العنوان</th>
                                <th>الكاتب</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>مقدمة في Laravel</td>
                                <td>أحمد محمد</td>
                                <td>2026-01-15</td>
                                <td class="actions">
                                    <button class="btn-edit">✏️ تعديل</button>
                                    <button class="btn-delete">🗑️ حذف</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>فهم Routing في Laravel</td>
                                <td>سارة خالد</td>
                                <td>2026-01-10</td>
                                <td class="actions">
                                    <button class="btn-edit">✏️ تعديل</button>
                                    <button class="btn-delete">🗑️ حذف</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Eloquent ORM</td>
                                <td>محمد علي</td>
                                <td>2026-01-05</td>
                                <td class="actions">
                                    <button class="btn-edit">✏️ تعديل</button>
                                    <button class="btn-delete">🗑️ حذف</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- نموذج إضافة منشور جديد -->
                <div class="admin-add-post">
                    <h2>➕ إضافة منشور جديد</h2>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="post_title">عنوان المنشور</label>
                            <input type="text" id="post_title" name="title" required placeholder="أدخل عنوان المنشور">
                        </div>
                        <div class="form-group">
                            <label for="post_content">محتوى المنشور</label>
                            <textarea id="post_content" name="content" rows="6" required placeholder="أدخل محتوى المنشور..."></textarea>
                        </div>
                        <div class="form-group">
                            <label for="post_category">التصنيف</label>
                            <select id="post_category" name="category">
                                <option value="">اختر تصنيف</option>
                                <option value="laravel">Laravel</option>
                                <option value="php">PHP</option>
                                <option value="frontend">Frontend</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary">📤 نشر المنشور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
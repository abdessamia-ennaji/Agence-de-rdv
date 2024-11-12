{{-- <x-app-layout> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDV</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4a90e2;
            --secondary: #f5f6fa;
            --text: #2d3436;
            --sidebar: #2c3e50;
            --transition: all 0.3s ease;
        }

        body {
            display: flex;
            background: #f0f2f5;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--sidebar);
            padding: 20px;
            color: white;
            position: fixed;
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar.collapsed {
            transform: translateX(-250px);
        }

        .logo {
            font-size: 24px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            padding: 15px 10px;
            cursor: pointer;
            transition: var(--transition);
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu li i {
            width: 20px;
        }

        .menu li:hover {
            background: rgba(255,255,255,0.1);
        }

        .menu li.active {
            background: var(--primary);
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text);
            font-size: 24px;
            cursor: pointer;
        }

        .search-bar {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
            max-width: 100%;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: var(--text);
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 24px;
            color: var(--primary);
            font-weight: bold;
        }


        .number-attente{
            font-size: 24px;
            color: #f39c12;
            font-weight: bold;
        }
        .number-annule{
            font-size: 24px;
            color: #e74c3c;
            font-weight: bold;
        }
        .number-confirme{
            font-size: 24px;
            color: #2ecc71;
            font-weight: bold;
        }

        .appointments-list {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: var(--secondary);
            white-space: nowrap;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            white-space: nowrap;
        }

        .confirmé { background: #dff9e3; color: #2ecc71; }
        .en-attente { background: #fff4e5; color: #f39c12; }
        .annulé { background: #ffe5e5; color: #e74c3c; }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        /* Responsive Design */
        @media screen and (max-width: 1024px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .overlay.active {
                display: block;
            }
        }

        @media screen and (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-bar {
                width: 100%;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 480px) {
            .main-content {
                padding: 10px;
            }

            .stat-card {
                padding: 15px;
            }
        }
    </style>
</head>


<body>
    <div class="overlay"></div>
    <div class="sidebar">
        <div class="logo">
            📅 Eureka RDV
            <button class="menu-toggle" id="close-sidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="menu">
            <li class="active"><i class="fas fa-th-large"></i> Dashboard</li>
            <li><i class="fas fa-calendar-alt"></i><a href="rdv/create/step1">Remplir un RDV</a> </li>
            <li><i class="fas fa-calendar-check"></i> <a href="rdv">Show All RDV</a> </li>
            <li><i class="fas fa-users"></i> Clients</li>
            <li><i class="fas fa-cog"></i> Settings</li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 20px;">
                <button class="menu-toggle" id="open-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Dashboard</h1>
            </div>
            <input type="text" class="search-bar" placeholder="Search appointments...">
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Today's RDV</h3>
                <div class="number">{{ $todaysRdvCount }}</div>
            </div>
            <div class="stat-card">
                <h3>En attente</h3>
                <div class="number-attente">{{ $enAttenteCount }}</div>
            </div>
            <div class="stat-card">
                <h3>Confirmé</h3>
                <div class="number-confirme">{{ $ConfirmeCount }}</div>
            </div>
            <div class="stat-card">
                <h3>Annulé</h3>
                <div class="number-annule">{{ $AnnuleCount }}</div>
            </div>
        </div>

        <div class="appointments-list">
            <h2>Recent RDV</h2>
            <table>
                <thead>
                    <tr>
                        <th>Prenom</th>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Time</th>
                        {{-- <th>Service</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="appointmentsList">
                    <!-- Appointments will be inserted here by JavaScript -->
                    @foreach ($recentRdvs as $rdv)
                    <tr>
                        <td>{{ $rdv->prenom }}</td>
                        <td>{{ $rdv->nom }}</td>
                        <td>{{ $rdv->rdv_date }}</td>
                        <td>{{ $rdv->rdv_time }}</td>
                        
                        <td>
                            @if($rdv->status)
                            <span class="status {{ strtolower(str_replace(' ', '-', $rdv->status->status)) }}">
                                    {{ $rdv->status->status }}
                                </span>
                            @else
                                <span class="status">No Status</span>
                            @endif
                        </td>


                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>

        <div class="chart-container">
            <h2>RDV Overview</h2>
            <canvas id="appointmentsChart"></canvas>
        </div>
    </div>

    <script>
        // Sidebar Toggle Functionality
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const overlay = document.querySelector('.overlay');
        const openSidebarBtn = document.getElementById('open-sidebar');
        const closeSidebarBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        openSidebarBtn.addEventListener('click', toggleSidebar);
        closeSidebarBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });





        const appointmentData = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], // Days of the week (Mon-Sat)
        data: {
            confirmés: @json($appointments['Confirmé']), // Dynamic data for Confirmé status
            enAttente: @json($appointments['En attente']), // Dynamic data for En Attente status
            annulés: @json($appointments['Annulé']) // Dynamic data for Annulé status
        }
    };


        // Create chart
        const ctx = document.getElementById('appointmentsChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: appointmentData.labels, // Use dynamic labels (Mon-Sat)
                datasets: [
                    {
                        label: 'Confirmé',
                        data: appointmentData.data.confirmés,  // Dynamic data for Confirmé status
                        borderColor: '#2ecc71',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    },
                    {
                        label: 'En Attente',
                        data: appointmentData.data.enAttente,  // Dynamic data for En Attente status
                        borderColor: '#f39c12',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(243, 156, 18, 0.1)',
                    },
                    {
                        label: 'Annulé',
                        data: appointmentData.data.annulés,  // Dynamic data for Annulé status
                        borderColor: '#e74c3c',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });





        // Search functionality
        const searchBar = document.querySelector('.search-bar');
        searchBar.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const rows = appointmentsList.getElementsByTagName('tr');
            
            Array.from(rows).forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Menu item click handler
        document.querySelectorAll('.menu li').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelector('.menu li.active').classList.remove('active');
                item.classList.add('active');
                if (window.innerWidth <= 1024) {
                    toggleSidebar();
                }
            });
        });
    </script>
</body>
</html>
{{-- </x-app-layout> --}}
<style>
    /* Global Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    /* Header Styles */
    header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      /* position: fixed;
      top: 0;
      left: 0;
      right: 0; */
      z-index: 100;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
    }

    /* Hero Section Styles */
    .hero {
      background-image: url('https://blog.gclb2b.com/hubfs/B2B%20Appointment%20Setting.jpg');
      background-size: cover;
      background-position: center;
      height: 500px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
      padding: 0 20px;
    }

    .hero h1 {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 24px;
      margin-bottom: 40px;
    }

    .cta-button {
      background-color: #007bff;
      color: #fff;
      padding: 12px 30px;
      border-radius: 5px;
      font-size: 18px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .cta-button:hover {
      background-color: #0056b3;
    }

    /* Footer Styles */
    footer {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }
  </style>
  



<x-app-layout>
    
    {{-- <h1>THIS IS THE USERS PAGE !!</h1> --}}
       

    <body>
        <header>
          <div class="logo">RDV</div>
          <nav>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </nav>
        </header>
      
        <main>
          <section class="hero">
            <h1>Prenez Rendez-Vous Dès Aujourd'hui.</h1>
            <p>Obtenez les soins dont vous avez besoin, quand vous en avez besoin.</p>
            <a href="#" class="cta-button">Prendre rendez-vous</a>
          </section>
        </main>
      
        <footer>
          <p>&copy; 2024 RDV. All rights reserved.</p>
        </footer>
</x-app-layout>
    

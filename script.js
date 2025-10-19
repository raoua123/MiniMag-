    const articles = [
      {id:1, title:"Créez votre coin créatif", excerpt:"Des astuces simples pour rendre votre bureau plus joyeux et productif.", category:"Design", image:"images/desk-creative.jpg", icon:"💡", time:"5 min", date:"12 Oct", class:"a1"},
      {id:2, title:"Les animaux qui volent votre cœur", excerpt:"Une collection d'instantanés trop mignons pour adoucir la journée.", category:"Lifestyle", image:"images/cute-pets.jpg", icon:"🐶", time:"3 min", date:"01 Sep", class:"a2"},
      {id:3, title:"Snacks rapides & jolis", excerpt:"3 recettes à faire en 10 minutes, parfaites pour les pauses créatives.", category:"Food", image:"images/healthy-snack.jpg", icon:"🍓", time:"4 min", date:"20 Août", class:"a3"},
      {id:4, title:"Mini guide: une journée en ville", excerpt:"Itinéraire cosy pour découvrir une ville en une journée.", category:"Travel", image:"images/city-travel.jpg", icon:"✈️", time:"7 min", date:"02 Juil", class:"a4"},
      {id:5, title:"Papeterie mignonne DIY", excerpt:"Personnalisez vos cahiers et cartes en quelques étapes simples.", category:"DIY", image:"images/stationery-diy.jpg", icon:"✂️", time:"6 min", date:"14 Juin", class:"a5"},
      {id:6, title:"Hackez vos révisions", excerpt:"Techniques de mémorisation et organisation pour étudiants efficaces.", category:"Study", image:"images/study-notes.jpg", icon:"📚", time:"8 min", date:"30 Mai", class:"a6"},
      {id:7, title:"Plantes faciles pour débutants", excerpt:"Choisissez des plantes résistantes et adorables pour débuter.", category:"Green", image:"images/plants-sunlight.jpg", icon:"🌿", time:"2 min", date:"12 Avr", class:"a7"},
      {id:8, title:"Routines du matin pour rester zen", excerpt:"Rituels simples pour un réveil doux et une journée productive.", category:"Culture", image:"images/morning-coffee.jpg", icon:"☕", time:"10 min", date:"01 Mar", class:"a8"},
      {id:9, title:"Édition spéciale: couleurs & motifs", excerpt:"Un grand dossier illustré sur les tendances joyeuses du moment.", category:"Special", image:"images/colorful-collage.jpg", icon:"✨", time:"15 min", date:"20 Fév", class:"a9"}
    ];

    const homeGrid = document.getElementById('home');
    articles.forEach((art, i) => {
      const article = document.createElement('article');
      article.className = `article ${art.class} reveal`;
      article.innerHTML = `
        <div class="media">
          <img src="${art.image}" alt="${art.title}" loading="lazy">
          <div class="badge">${art.category}</div>
        </div>
        <div class="content">
          <div class="kicker">${art.category}</div>
          <div class="title">${art.title}</div>
          <div class="excerpt">${art.excerpt}</div>
          <div class="meta">
            <div class="icon">${art.icon}</div>
            <div class="meta-text">${art.time} • ${art.date}</div>
          </div>
        </div>
      `;
      article.addEventListener('click', () => openArticle(art));
      homeGrid.appendChild(article);
    });

    const pages = document.getElementById('pages');
    const homeBtn = document.getElementById('homeBtn');
    const openArticleBtn = document.getElementById('openArticleBtn');
    const backHome = document.getElementById('backHome');

    function goToSpread(){ 
      pages.classList.remove('slide-left'); 
      pages.classList.add('slide-right'); 
    }
    function goHome(){ 
      pages.classList.remove('slide-right'); 
      pages.classList.add('slide-left'); 
    }

    function openArticle(art){
      document.getElementById('spreadTitle').textContent = art.title;
      document.getElementById('spreadExcerpt').textContent = art.excerpt;
      document.getElementById('spreadCategory').textContent = art.category;
      document.getElementById('spreadImg').src = art.image;
      document.getElementById('spreadTime').textContent = art.time;
      document.getElementById('spreadDate').textContent = art.date;
      goToSpread();
    }

    homeBtn.addEventListener('click', goHome);
    openArticleBtn.addEventListener('click', () => openArticle(articles[0]));
    backHome.addEventListener('click', goHome);

    document.addEventListener('keydown', e=>{
      if(e.key==='ArrowRight') goToSpread();
      if(e.key==='ArrowLeft') goHome();
    });

    window.addEventListener('load', ()=>{
      document.getElementById('loader').style.display='none';
      document.querySelectorAll('.reveal').forEach((el,i)=>
        setTimeout(()=>el.classList.add('show'), 80*i)
      );
    });

    const observer = new IntersectionObserver((entries)=>{
      entries.forEach(en=>{ if(en.isIntersecting) en.target.classList.add('show'); });
    },{threshold:0.1});
    document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));

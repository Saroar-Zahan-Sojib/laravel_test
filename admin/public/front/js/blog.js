    
    
    var pc = window.matchMedia("(min-width: 801px)");
    var tb = window.matchMedia("(min-width: 501px)");
    var mb = window.matchMedia("(max-width: 500px)");
    
    if(mb.matches){
        var menu = document.querySelector('.threeDot');
        var menuIcon = document.querySelector('.threeDot i');
        var links = document.querySelector('.links');
        var menuClicked=0;
        menu.addEventListener('click', ()=> {
            menuClicked++;
            if(menuClicked==1){
                menuIcon.innerHTML = 'close';
                links.style.transform = 'translateX(0%)';
            }
            else{
                menuIcon.innerHTML = 'menu';
                links.style.transform = 'translateX(100%)';
                menuClicked=0;
            }
        });
        var link = document.querySelectorAll('.link');
        console.log(link);
        for (var i=0; i<link.length; i++)
        {
            link[i].addEventListener('click', ()=>{
                menuIcon.innerHTML = 'menu';
                links.style.transform = 'translateX(100%)';
                menuClicked=0;
            });
        }
    }

    const modal = document.querySelector('.mod-container');

    var isModalOpen = 0;
    function openModal() {
        modal.classList.add('open');
        isModalOpen=1;
    }
    function closeModal() {
        modal.classList.remove('open');
        isModalOpen=0;
    }
    setInterval(() => {
        if(isModalOpen == 0){
            modal.classList.add('open');
        }
    }, 30000);


    var scrollArea = window.innerHeight;
    var blogText = document.querySelectorAll('.blog-text');
    console.log(blogText);


        for (var i=0; i<blogText.length; i++)
        {
            blogText[i].classList.add('bltext');
        }
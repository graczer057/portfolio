/* Creating const variables that select items with id's*/
const navMenu = document.querySelector(".nav-menu");
const roller = document.querySelector(".roller");
const cta = document.querySelector(".cta");

/* If statement checking, are variables existing on the current page */
if(navMenu && roller && cta){

   /* Adding event listener on click on roller to enable mobile format menu active */
   roller.addEventListener("click", () => {
      roller.classList.toggle("is-active");
      navMenu.classList.toggle("is-active");
      cta.classList.toggle("is-active");
   })

   /* Adding event listener on click on roller to disable mobile format menu active */
   document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
      roller.classList.remove("is-active");
      navMenu.classList.remove("is-active");
      cta.classList.remove("is-active");
   }))
}

/* Event listener while scrolling is executing a reveal function */
window.addEventListener('scroll', reveal);

function reveal(){
   /* Variable containing all selectors with class reveal */
   let reveals = document.querySelectorAll('.reveal');

   /* For loop to change which section must be visible or hidden */
   for(let i = 0; i < reveals.length; i++){
      /* Variables with height of screen and data to check bellow when to reveal */
      let windowHeight = window.innerHeight;
      let revealTop = reveals[i].getBoundingClientRect().top;
      let revealPoint = 150;

      /* If statement to enable or disable revealed section/s */
      if(revealTop < windowHeight - revealPoint){
         reveals[i].classList.add('active');
      }else{
         reveals[i].classList.remove('active');
      }
   }
}

/* Creating const variables that select items with id's*/
const cl = document.getElementById("cM");
const alert = document.getElementById("alert");

/* If statement checking, are variables existing on the current page */
if(cl && alert){

   /* This event listener is closing alert message */
   cl.addEventListener("click", () => {
      alert.classList.remove("show");
   })
}
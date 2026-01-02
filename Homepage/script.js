document.getElementById('loginBtn').onclick = function(e) {
    e.preventDefault();
    document.getElementById('authModal').style.display = 'block';
};
document.querySelector('.close').onclick = function() {
    document.getElementById('authModal').style.display = 'none';
};
window.onclick = function(event) {
    if (event.target == document.getElementById('authModal')) {
        document.getElementById('authModal').style.display = 'none';
    }
};
function showForm(form) {
    document.getElementById('loginForm').style.display = (form === 'login') ? 'block' : 'none';
    document.getElementById('registerForm').style.display = (form === 'register') ? 'block' : 'none';
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelector('.tab.' + form).classList.add('active');
}


// ✅ Simple carousel effect for testimonials
const testimonials = document.querySelectorAll('.testimonial');
const nextBtn = document.querySelector('.next');
const prevBtn = document.querySelector('.prev');

let index = 0;

function showTestimonial(i) {
  testimonials.forEach((t, idx) => {
    t.style.transform = `translateX(${(idx - i) * 320}px)`;
  });
}

showTestimonial(index);

nextBtn.addEventListener('click', () => {
  index = (index + 1) % testimonials.length;
  showTestimonial(index);
});

prevBtn.addEventListener('click', () => {
  index = (index - 1 + testimonials.length) % testimonials.length;
  showTestimonial(index);
});

 

 
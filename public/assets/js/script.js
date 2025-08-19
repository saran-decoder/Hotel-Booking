$(document).ready(function() {
    // Smooth scrolling for anchor links
    $('a[href*="#"]').on('click', function(e) {
        e.preventDefault();
        
        $('html, body').animate(
            {
                scrollTop: $($(this).attr('href')).offset().top - 80,
            },
            500,
            'linear'
        );
    });
    
    // Add active class to navbar items on scroll
    $(window).scroll(function() {
        var scrollDistance = $(window).scrollTop();
        
        $('section').each(function(i) {
            if ($(this).position().top <= scrollDistance + 100) {
                $('.navbar a.active').removeClass('active');
                $('.navbar a').eq(i).addClass('active');
            }
        });
    }).scroll();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Animate elements when they come into view
    function animateOnScroll() {
        $('.feature-card, .destination-card, .offer-card').each(function() {
            var position = $(this).offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            
            if (scroll + windowHeight > position + 100) {
                $(this).addClass('animated');
            }
        });
    }
    
    $(window).on('scroll', animateOnScroll);
    animateOnScroll();
});


$(document).ready(function() {
    const scroller = $('.destinations-scroller');
    const track = $('.destinations-track');
    const prevBtn = $('.scroller-prev');
    const nextBtn = $('.scroller-next');
    const cards = $('.destination-card');
    const cardWidth = cards.outerWidth(true) + 20; // width + margin
    
    // Calculate scroll amount (3 cards at a time)
    const scrollAmount = cardWidth * 3;
    
    // Next button click
    nextBtn.on('click', function() {
        const currentScroll = scroller.scrollLeft();
        const maxScroll = track.outerWidth() - scroller.outerWidth();
        
        if (currentScroll + scrollAmount >= maxScroll) {
            scroller.animate({scrollLeft: maxScroll}, 400);
        } else {
            scroller.animate({scrollLeft: currentScroll + scrollAmount}, 400);
        }
    });
    
    // Previous button click
    prevBtn.on('click', function() {
        const currentScroll = scroller.scrollLeft();
        
        if (currentScroll - scrollAmount <= 0) {
            scroller.animate({scrollLeft: 0}, 400);
        } else {
            scroller.animate({scrollLeft: currentScroll - scrollAmount}, 400);
        }
    });
    
    // Hide/show buttons based on scroll position
    function updateButtonVisibility() {
        const scrollLeft = scroller.scrollLeft();
        const maxScroll = track.outerWidth() - scroller.outerWidth();
        
        prevBtn.toggle(scrollLeft > 5);
        nextBtn.toggle(scrollLeft < maxScroll - 5);
    }
    
    scroller.on('scroll', updateButtonVisibility);
    updateButtonVisibility();
    
    // Touch/swipe support
    let isDown = false;
    let startX, scrollLeft;
    
    scroller.on('mousedown touchstart', function(e) {
        isDown = true;
        startX = (e.pageX || e.originalEvent.touches[0].pageX) - scroller.offset().left;
        scrollLeft = scroller.scrollLeft();
    });
    
    scroller.on('mouseleave mouseup touchend', function() {
        isDown = false;
    });
    
    scroller.on('mousemove touchmove', function(e) {
        if(!isDown) return;
        e.preventDefault();
        const x = (e.pageX || e.originalEvent.touches[0].pageX) - scroller.offset().left;
        const walk = (x - startX) * 2;
        scroller.scrollLeft(scrollLeft - walk);
    });
});


const dropdownBtn = document.getElementById("dropdownBtn");
const dropdownPanel = document.getElementById("dropdownPanel");

let adults = 1;
let children = 1;

dropdownBtn.addEventListener("click", () => {
    dropdownPanel.style.display = dropdownPanel.style.display === "block" ? "none" : "block";
});

function changeCount(type, change) {
    if (type === "adults") {
    adults = Math.max(1, adults + change);
    document.getElementById("adultCount").innerText = adults;
    } else if (type === "children") {
    children = Math.max(1, children + change);
    document.getElementById("childCount").innerText = children;
    }
}

function applySelection() {
    dropdownBtn.innerText = `${adults} Adult${adults > 1 ? 's' : ''}, ${children} Child${children > 1 ? 'ren' : ''}`;
    dropdownPanel.style.display = "none";
}

// Optional: Hide dropdown when clicking outside
document.addEventListener("click", (e) => {
    if (!e.target.closest(".dropdown-container")) {
    dropdownPanel.style.display = "none";
    }
});
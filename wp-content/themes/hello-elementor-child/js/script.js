// Get the first 'main' element from the document


// window.onload = function() {
//   const loader = document.getElementById('loader');
//   loader.style.display = 'none';  // Hide the loader
//   content.style.visibility = 'visible'; // Show the content
// };

//session user for not logged in add to cart

// var userCookieId = document.cookie.replace(/(?:(?:^|.*;\s*)user_cookie_id\s*\=\s*([^;]*).*$)|^.*$/, "$1");
// console.log('User Cookie ID:', userCookieId);
// document.addEventListener('DOMContentLoaded', function() {
//   // Check if the cart link exists
//   if (cartPopupData.restoreCartLink) {
//       // Create a popup div
//       const popup = document.createElement('div');
//       popup.id = 'cart-popup';
//       popup.style.display = 'none';
//       popup.style.position = 'fixed';
//       popup.style.top = '20%';
//       popup.style.left = '50%';
//       popup.style.transform = 'translate(-50%, -50%)';
//       popup.style.background = 'white';
//       popup.style.padding = '20px';
//       popup.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
//       popup.style.zIndex = '9999';

//       // Add content to the popup
//       popup.innerHTML = `
//           <p>You can save or share your cart with this link:</p>
//           <a href="${cartPopupData.restoreCartLink}">${cartPopupData.restoreCartLink}</a>
//           <button id="close-popup" style="margin-top: 10px; display: block; background: #0073aa; color: white; border: none; padding: 10px 15px; cursor: pointer;">Close</button>
//       `;

//       // Append popup to body
//       document.body.appendChild(popup);

//       // Show the popup when the user opens a new tab or window
//       window.addEventListener('focus', function() {
//           popup.style.display = 'block';
//       });

//       // Close the popup on button click
//       document.addEventListener('click', function(event) {
//           if (event.target.id === 'close-popup') {
//               popup.style.display = 'none';
//           }
//       });
//   }
// });



//End of session user for not logged in add to cart



document.addEventListener("DOMContentLoaded", function () {
      //Read More Button link overridden for the THE SHOOT E R ’S SCOPE Blog
  const blogPosts = document.querySelectorAll("h2");

  blogPosts.forEach((heading) => {
    if (heading.textContent.trim() === "THE SHOOTER’S SCOPE") {
      const blogPost = heading.closest(".post");
      console.log(blogPost, "Blogpost");

      if (blogPost) {
        const readMoreLink = blogPost.querySelector(".read-more");
        console.log(readMoreLink, "READpost");
        if (readMoreLink) {
          readMoreLink.href =
            "https://colonelzsharpshooterz.com/wp-content/uploads/2024/08/The-Shooters-Scope_Aug2024.pdf";
            readMoreLink.target = "_blank";
        }
      }
    }
  });
  
  const blogPosts2 = document.querySelectorAll("h2");
  blogPosts2.forEach((heading2) => {
  if (heading2.textContent.trim() === "THE SHOOTER’S SCOPES") {
    const blogPost2 = heading2.closest(".post");
    console.log(blogPost2, "Blogpost");

    if (blogPost2) {
      const readMoreLink2 = blogPost2.querySelector(".read-more");
      console.log(readMoreLink2, "READpost");
      if (readMoreLink2) {
        readMoreLink2.href =
          "https://colonelzsharpshooterz.com/wp-content/uploads/2024/09/The-Shooters-Scope-Vol-2.pdf";
          readMoreLink2.target = "_blank";
      }
    }
  }
});

  
  // Ensure all images have alt attributes
  var images = document.querySelectorAll("img");
  images.forEach(function (img) {
    if (!img.hasAttribute("alt") || img.getAttribute("alt") === "") {
      img.setAttribute("alt", "colonelzsharpshooterz");
    }
  });

  // Handle marquee animation for off-sale element
  var offSaleElement = document.getElementById("off-sale");
  if (
    offSaleElement &&
    offSaleElement.scrollWidth > offSaleElement.clientWidth
  ) {
    var marqueeSpan = offSaleElement.querySelector(".marquee");
    marqueeSpan.style.animationPlayState = "running";
  }

  // Add Google rating div on the home page
  if (document.body.classList.contains("page-name-home")) {
    var newDiv = document.createElement("div");
    newDiv.className = "google-rating";
    newDiv.textContent = "4.8/5";

    // Select the reference node
    var referenceNode = document.querySelector(".ti-header .ti-rating-text");

    if (referenceNode) {
      // Insert the new div after the reference node
      referenceNode.parentNode.insertBefore(newDiv, referenceNode.nextSibling);
    }
  }

  // Toggle filter visibility
  const aElement = document.querySelector(
    "a.wpc-open-close-filters-button.wpc-show-counts-no"
  );
  if (aElement) {
    aElement.addEventListener("click", function () {
      this.classList.toggle("wpc-opened");

      // Select the <div> element
      const divElement = document.querySelector("div.wc-blocks-filter-wrapper");

      // Check if the <a> element has the 'wpc-opened' class and add/remove the 'open' class on the <div> element
      if (divElement) {
        if (this.classList.contains("wpc-opened")) {
          divElement.classList.add("open");
        } else {
          divElement.classList.remove("open");
        }
      }
    });
  }


});

 // Show the button when scrolling down
 window.addEventListener('scroll', function () {
  const backToTopButton = document.getElementById('backToTop');
  if (window.scrollY > 300) { // Show after scrolling 300px
    backToTopButton.style.display = 'block';
  } else {
    backToTopButton.style.display = 'none';
  }
});

// Scroll back to top when clicked
document.getElementById('backToTop').addEventListener('click', function () {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

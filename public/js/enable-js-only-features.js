"use strict";

// shows all elements that are exclusively working with js
(function() {
    window.addEventListener("DOMContentLoaded", async event => {
        // shows all elements only if script is enabled
        let hiddenElements = document.querySelectorAll(".no-js-hide");
        for (let hiddenElement of hiddenElements) {
            hiddenElement.classList.remove("no-js-hide");
        }

        // hides all elements that are not necessary for js
        let showElements = document.querySelectorAll(".no-js-container");
        for (let showElement of showElements) {
            showElement.style.display = "none";
        }

        // call an event if needed
        let allowJSEvent = new Event("jsallowed");
        window.dispatchEvent(allowJSEvent);
    })
})();
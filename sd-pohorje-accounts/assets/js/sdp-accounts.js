(function () {
    function sanitizeUsernamePart(value) {
        return (value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9._-]/g, '');
    }

    function unique(values) {
        var out = [];
        var seen = {};

        values.forEach(function (value) {
            if (!value || seen[value]) {
                return;
            }

            seen[value] = true;
            out.push(value);
        });

        return out;
    }

    function buildUsernameCandidates(firstName, lastName) {
        var first = sanitizeUsernamePart(firstName);
        var last = sanitizeUsernamePart(lastName);

        if (!first || !last) {
            return [];
        }

        var firstInitial = first.charAt(0);
        var lastInitial = last.charAt(0);

        var base = unique([
            first + '.' + last,
            first + last,
            firstInitial + last,
            first + lastInitial,
            last + '.' + first,
            first + '-' + last,
        ]);

        var suggestions = base.slice();

        base.forEach(function (candidate) {
            suggestions.push(candidate + '1');
            suggestions.push(candidate + '2');
        });

        return unique(suggestions).slice(0, 8);
    }

    function setSelectOptions(select, candidates) {
        var previous = select.value;
        select.innerHTML = '';

        if (!candidates.length) {
            var emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = 'Najprej vnesite ime in priimek';
            select.appendChild(emptyOption);
            select.value = '';
            return;
        }

        candidates.forEach(function (candidate) {
            var option = document.createElement('option');
            option.value = candidate;
            option.textContent = candidate;
            select.appendChild(option);
        });

        if (previous && candidates.indexOf(previous) !== -1) {
            select.value = previous;
            return;
        }

        select.value = candidates[0];
    }

    function initRegistrationUsernameSuggestions() {
        var forms = document.querySelectorAll('.sdp-registration-form');

        forms.forEach(function (form) {
            var firstNameInput = form.querySelector('input[name="first_name"]');
            var lastNameInput = form.querySelector('input[name="last_name"]');
            var usernameSelect = form.querySelector('.sdp-username-select');

            if (!firstNameInput || !lastNameInput || !usernameSelect) {
                return;
            }

            var refresh = function () {
                var candidates = buildUsernameCandidates(firstNameInput.value, lastNameInput.value);
                setSelectOptions(usernameSelect, candidates);
            };

            firstNameInput.addEventListener('input', refresh);
            lastNameInput.addEventListener('input', refresh);

            refresh();
        });
    }

    function initClubShopCart() {
        var shopWraps = document.querySelectorAll('.sdp-club-shop-wrap');

        shopWraps.forEach(function (wrap) {
            var cart = [];
            var countEl = wrap.querySelector('.sdp-club-cart-count');
            var totalEl = wrap.querySelector('.sdp-club-cart-total');
            var emptyEl = wrap.querySelector('.sdp-club-cart-empty');
            var itemsEl = wrap.querySelector('.sdp-club-cart-items');
            var messageEl = wrap.querySelector('.sdp-club-cart-message');
            var submitForm = wrap.querySelector('.sdp-club-cart-submit');
            var payloadInput = submitForm ? submitForm.querySelector('input[name="club_cart_payload"]') : null;
            var submitButton = submitForm ? submitForm.querySelector('button[type="submit"]') : null;

            if (!countEl || !totalEl || !emptyEl || !itemsEl || !messageEl || !payloadInput || !submitButton) {
                return;
            }

            function formatPrice(value) {
                return value.toFixed(2).replace('.', ',') + ' EUR';
            }

            function setMessage(text) {
                if (!text) {
                    messageEl.hidden = true;
                    messageEl.textContent = '';
                    return;
                }

                messageEl.hidden = false;
                messageEl.textContent = text;
            }

            function renderCart() {
                var totalItems = 0;
                var totalAmount = 0;
                var html = '';

                cart.forEach(function (item, index) {
                    var quantity = Number(item.quantity) || 1;
                    var unitPrice = parseFloat(item.price || '0');
                    totalItems += quantity;
                    if (!isNaN(unitPrice)) {
                        totalAmount += unitPrice * quantity;
                    }
                    html += '<div class="sdp-club-cart-row">';
                    html += '<div class="sdp-club-cart-row-main">';
                    html += '<strong>' + item.name + '</strong>';
                    html += '<span>Količina: ' + quantity + '</span>';

                    if (item.size) {
                        html += '<span>Velikost: ' + item.size + '</span>';
                    }

                    if (item.priceLabel) {
                        html += '<span>Cena: ' + item.priceLabel + '</span>';
                    }

                    html += '</div>';
                    html += '<button type="button" class="sdp-btn-danger sdp-club-remove-item" data-cart-index="' + index + '">Odstrani</button>';
                    html += '</div>';
                });

                itemsEl.innerHTML = html;
                emptyEl.style.display = cart.length ? 'none' : 'block';
                countEl.textContent = totalItems + (totalItems === 1 ? ' izdelek' : ' izdelkov');
                totalEl.textContent = 'Skupaj: ' + formatPrice(totalAmount);
                payloadInput.value = JSON.stringify(cart.map(function (item) {
                    return {
                        product_id: item.productId,
                        quantity: item.quantity,
                        size: item.size,
                    };
                }));
                submitButton.disabled = cart.length === 0;
            }

            function addToCart(button) {
                var card = button.closest('.sdp-club-card');
                var quantityInput;
                var sizeInput;
                var sizeValue = '';
                var quantity;
                var productId;
                var existingItem;

                if (!card) {
                    return;
                }

                quantityInput = card.querySelector('input[name="club_quantity"]');
                sizeInput = card.querySelector('[name="club_size"]');
                quantity = quantityInput ? parseInt(quantityInput.value, 10) : 1;
                productId = parseInt(card.getAttribute('data-product-id') || '0', 10);

                if (!productId || !quantity || quantity < 1) {
                    setMessage('Izberite veljavno količino izdelka.');
                    return;
                }

                if (sizeInput) {
                    sizeValue = (sizeInput.value || '').trim();

                    if (sizeInput.tagName === 'SELECT' && !sizeValue) {
                        setMessage('Pred dodajanjem v košarico izberite velikost izdelka.');
                        return;
                    }
                }

                existingItem = cart.find(function (item) {
                    return item.productId === productId && item.size === sizeValue;
                });

                if (existingItem) {
                    existingItem.quantity += quantity;
                } else {
                    cart.push({
                        productId: productId,
                        name: card.getAttribute('data-product-name') || '',
                        price: card.getAttribute('data-product-price') || '',
                        priceLabel: card.getAttribute('data-product-price-label') || '',
                        quantity: quantity,
                        size: sizeValue,
                    });
                }

                setMessage('Izdelek je dodan v košarico.');
                if (quantityInput) {
                    quantityInput.value = '1';
                }
                renderCart();
            }

            wrap.addEventListener('click', function (event) {
                var addButton = event.target.closest('.sdp-club-add-to-cart');
                var removeButton = event.target.closest('.sdp-club-remove-item');
                var index;

                if (addButton) {
                    event.preventDefault();
                    addToCart(addButton);
                    return;
                }

                if (removeButton) {
                    event.preventDefault();
                    index = parseInt(removeButton.getAttribute('data-cart-index') || '-1', 10);

                    if (index >= 0) {
                        cart.splice(index, 1);
                        setMessage(cart.length ? 'Izdelek je odstranjen iz košarice.' : 'Košarica je zdaj prazna.');
                        renderCart();
                    }
                }
            });

            renderCart();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var wrapElements = document.querySelectorAll('.sdp-auth-wrap');
        if (!wrapElements.length) {
            initRegistrationUsernameSuggestions();
            initClubShopCart();
            return;
        }

        wrapElements.forEach(function (el, index) {
            el.style.animationDelay = (index * 90) + 'ms';
        });

        initRegistrationUsernameSuggestions();
        initClubShopCart();
    });
})();

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

    document.addEventListener('DOMContentLoaded', function () {
        var wrapElements = document.querySelectorAll('.sdp-auth-wrap');
        if (!wrapElements.length) {
            initRegistrationUsernameSuggestions();
            return;
        }

        wrapElements.forEach(function (el, index) {
            el.style.animationDelay = (index * 90) + 'ms';
        });

        initRegistrationUsernameSuggestions();
    });
})();

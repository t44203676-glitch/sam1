</div>
<script>
/**
 * Convert ALL Eastern Arabic digits to Western digits (0-9)
 * Uses Unicode escapes (\u0660-\u0669, \u06F0-\u06F9) for encoding safety
 */
(function() {
    function toWestern(str) {
        return str.replace(/[\u0660-\u0669\u06F0-\u06F9]/g, function(d) {
            var code = d.charCodeAt(0);
            if (code >= 0x0660 && code <= 0x0669) return String(code - 0x0660);
            if (code >= 0x06F0 && code <= 0x06F9) return String(code - 0x06F0);
            return d;
        });
    }
    function walkNodes(node) {
        if (node.nodeType === 3) {
            var t = node.textContent;
            var n = toWestern(t);
            if (t !== n) node.textContent = n;
        } else {
            for (var i = 0; i < node.childNodes.length; i++) {
                walkNodes(node.childNodes[i]);
            }
        }
    }
    function convertInputs() {
        var inputs = document.querySelectorAll('input, textarea');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].value) {
                inputs[i].value = toWestern(inputs[i].value);
            }
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { walkNodes(document.body); convertInputs(); });
    } else {
        walkNodes(document.body);
        convertInputs();
    }
})();
</script>
</body>
</html>


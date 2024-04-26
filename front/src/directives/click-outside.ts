// directives/click-outside.ts
import { ObjectDirective } from 'vue';

const clickOutside: ObjectDirective = {
    beforeMount(el, binding) {
        el.clickOutsideHandler = function (event: MouseEvent) {
            // Vérifiez si le clic est en dehors et le menu est ouvert avant de fermer
            if (!(el.contains(event.target as Node)) && binding.value.open.value) {
                binding.value.close();
            }
        };
        document.addEventListener('click', el.clickOutsideHandler);
    },
    unmounted(el) {
        document.removeEventListener('click', el.clickOutsideHandler);
    }
};

export default clickOutside;

let optionCount = 2;

function addOption() {

    optionCount++;

    const container = document.getElementById('options-container');

    const div = document.createElement('div');
    div.className = "flex items-center gap-2 option-row";

    div.innerHTML = `
        <input
            type="text"
            name="options[]"
            placeholder="Option ${optionCount}"
            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm"
            required
        >

        <button type="button"
            onclick="removeOption(this)"
            class="px-2 py-1 text-red-500 hover:text-red-700">
            ✕
        </button>
    `;

    container.appendChild(div);

}

function removeOption(button) {

    const row = button.closest('.option-row');

    const container = document.getElementById('options-container');

    if (container.children.length > 2) {
        row.remove();
    }

}

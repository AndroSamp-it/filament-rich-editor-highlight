import { Mark, mergeAttributes } from '@tiptap/core'

// name: 'highlight' — replaces the built-in single-colour Filament Highlight extension
// (Filament's extensions.js swaps extensions by matching .name in the array).
// Defined via Mark.create() to avoid inheritance/parent-chain issues with extend()/configure().
export default Mark.create({
    name: 'highlight',

    addOptions() {
        return {
            HTMLAttributes: {},
        }
    },

    addAttributes() {
        return {
            color: {
                default: null,
                parseHTML: element =>
                    element.getAttribute('data-color') ||
                    element.style.backgroundColor ||
                    null,
                renderHTML: attributes => {
                    if (!attributes.color) return {}
                    return {
                        'data-color': attributes.color,
                        style: `background-color: ${attributes.color}; color: inherit`,
                    }
                },
            },
        }
    },

    parseHTML() {
        return [{ tag: 'mark' }]
    },

    renderHTML({ HTMLAttributes }) {
        return ['mark', mergeAttributes(this.options.HTMLAttributes, HTMLAttributes), 0]
    },

    addCommands() {
        return {
            setHighlight:
                attributes =>
                ({ commands }) =>
                    commands.setMark(this.name, attributes),
            toggleHighlight:
                attributes =>
                ({ commands }) =>
                    commands.toggleMark(this.name, attributes),
            unsetHighlight:
                () =>
                ({ commands }) =>
                    commands.unsetMark(this.name),
        }
    },
})



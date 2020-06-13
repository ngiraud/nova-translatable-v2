export default {
    data: () => ({
        value: {},
        activeLocale: '',
    }),

    created() {
        this.value = this.getInitialValue()
        this.activeLocale = this.getActiveLocale()

        // Listen to "all locales" event
        Nova.$on('nova-translatable@setAllLocale', this.setLocale)
    },

    destroyed() {
        Nova.$off('nova-translatable@setAllLocale', this.setLocale)
    },

    computed: {
        locales() {
            return Object.keys(this.field.translatable.locales).map(key => ({
                key,
                name: this.field.translatable.locales[key],
            }))
        },

        localizedValueMustBeAnObject() {
            return this.field.translatable.localized_value_is_an_object
        }
    },

    methods: {
        setInitialValue() {
            // Do nothing
        },

        getInitialValue() {
            const initialValue = {}

            for (const locale of this.locales) {
                initialValue[locale.key] = this.field.translatable.value[locale.key] || ''
            }

            return initialValue
        },

        getActiveLocale() {
            let localesFiltered = this.locales.filter(({key}) => key === Nova.config.locale)

            if (localesFiltered.length === 0) {
                localesFiltered = this.locales
            }

            return localesFiltered[0].key
        },

        setLocale(newLocale) {
            this.activeLocale = newLocale
        },

        setAllLocale(newLocale) {
            Nova.$emit('nova-translatable@setAllLocale', newLocale)
        },

        removeBottomBorder() {
            if (!this.$refs.main) {
                return false
            }

            return this.$refs.main.classList.contains('remove-bottom-border')
        },

        getComponentField(locale) {
            let value = this.value[locale.key]

            if (this.localizedValueMustBeAnObject && Object.keys(value).length <= 0) {
                value = ''
            }

            return {
                ...this.field,
                value: value || '',
                validationKey: `${this.field.validationKey}.${locale.key}`,
                attribute: `${this.field.attribute}.${locale.key}`,
            }
        }
    },
}

<template>
    <div class="translatable-field" ref="main">
        <locale-tabs
            :locales="locales"
            :active-locale="activeLocale"
            :errors="errors"
            :error-attributes="errorAttributes"
            @tabClick="setLocale"
            @doubleClick="setAllLocale"
        />

        <div v-for="locale in locales" :key="locale.key">
            <component
                v-show="locale.key === activeLocale"
                :is="'form-' + field.translatable.original_component"
                :field="getComponentField(locale)"
                :resource-name="resourceName"
                :errors="errors"
                :ref="`${locale.key}Component`"
                :class="{ 'remove-bottom-border': removeBottomBorder() }"
            ></component>
        </div>
    </div>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'
    import TranslatableField from '../mixins/TranslatableField'
    import LocaleTabs from './LocaleTabs'

    export default {
        components: {LocaleTabs},

        mixins: [HandlesValidationErrors, FormField, TranslatableField],

        props: ['field', 'resourceId', 'resourceName'],

        methods: {
            fill(formData) {
                // Add value to FormData
                for (const locale of this.locales) {
                    formData.append(
                        `${this.field.attribute}[${locale.key}]`,
                        this.localizedValueMustBeAnObject && this.value[locale.key] !== ''
                            ? JSON.stringify(this.value[locale.key])
                            : this.value[locale.key]
                    )
                }
            },
        },

        computed: {
            errorAttributes() {
                const errorAttributes = {}

                for (const locale of this.locales) {
                    errorAttributes[locale.key] = `${this.field.attribute}.${locale.key}`
                }

                return errorAttributes
            },

            attributeToWatch() {
                return this.localizedValueMustBeAnObject ? 'finalPayload' : 'value'
            }
        },

        created() {
            this.$nextTick(() => {
                for (const locale of this.locales) {
                    // We set a watcher on each localized value to auto update
                    this.$watch(
                        `$refs.${locale.key}Component.0.${this.attributeToWatch}`,
                        (newValue) => {
                            this.value[locale.key] = newValue
                        }
                    )
                }
            })
        }
    }
</script>

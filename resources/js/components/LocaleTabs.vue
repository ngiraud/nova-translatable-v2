<template>
    <div class="pt-4 flex select-none" :class="{'px-8': !detail}">
        <div class="ml-auto">
            <a
                v-for="locale in sortedLocales"
                :key="locale.key"
                class="ml-3 cursor-pointer font-bold text-80 text-sm"
                :class="{
                  'text-primary border-b-2 border-primary': locale.key === activeLocale,
                  'text-danger border-danger': hasError(locale.key),
                }"
                @click="$emit('tabClick', locale.key)"
                @dblclick="$emit('doubleClick', locale.key)"
            >
                {{ locale.name }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['locales', 'activeLocale', 'detail', 'errors', 'errorAttributes'],

        computed: {
            sortedLocales() {
                const novaLocale = _.find(this.locales, ['key', Nova.config.locale])

                if (!novaLocale) {
                    return this.locales
                }

                return [novaLocale, ...this.locales.filter(({key}) => key !== Nova.config.locale)]
            }
        },

        methods: {
            hasError(locale) {
                if (!this.errors || !this.errorAttributes) {
                    return false
                }

                return this.errors.has(this.errorAttributes[locale])
            },
        },
    }
</script>

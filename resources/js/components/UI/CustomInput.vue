<template>
    <v-text-field v-if="type !== 'password'" 
        color="#FA845C" 
        v-model="localValue"
        :type="type"
        :label="label"  
        :error-messages="errors"  
        outlined
        dense
    >
        <template v-slot:error="{ errors }">
            <span v-if="shouldShowErrors(errors)">{{ errors }}</span>
        </template>
    </v-text-field>

    <v-text-field v-else 
        color="#FA845C" 
        v-model="localValue"
        :label="label"  
        :error-messages="errors"  

        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
        :type="show ? 'text' : 'password'"

        outlined
        dense
        @click:append="show = !show"

    >
    <template v-slot:error="{ errors }">
        <span v-if="shouldShowErrors(errors)">{{ errors }}</span>
    </template>
    </v-text-field>
</template>

<script>
export default {
    name: 'custom-input',

    props: {
        value: {
            type: String,
            default: ''
        },
        type: {
            type: String
        },
        label: {
            type: String
        },
        errors: {
            type: Array,
        },
        icon: {
            type:String
        }
    },

    data: () => ({
        show: false,
    }),

    computed: {
        shouldShowErrors() {
            return this.errors !== '';
        },
        localValue: {
            get() {
                return this.value;
            },
            set(newValue) {
                // Вы можете добавить дополнительную логику здесь, если необходимо
                this.$emit('input', newValue);
            }
        }
    },
}
</script>
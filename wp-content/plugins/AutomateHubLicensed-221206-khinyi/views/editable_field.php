<script type="text/template" id="editable-field-template">
    <tr class="alternate" v-if="action.task == field.task">
        <td>
            <label for="tablecell" class="sperse_form_label">
                {{field.title}}
            </label>
        </td>
        <td class="form_field_dropable">
            <input type="text" ref="fieldValue" class="basic-text" v-model="fielddata[field.value]"  v-bind:required="field.required">
            <div class="fx-placeholder-label"><span>Drop your {{field.title}} form field here. </span></div>
        </td>
    </tr>
</script>
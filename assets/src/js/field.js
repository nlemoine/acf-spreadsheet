import "jspreadsheet-ce";
import "jspreadsheet-ce/dist/jspreadsheet.css";
import "jspreadsheet-ce/dist/jspreadsheet.datatables.css";

(function ($, undefined) {
  var Field = acf.Field.extend({
    type: "spreadsheet",
    wait: "ready",
    $control: function () {
      return this.$(".acf-spreadsheet-control");
    },
    $input: function () {
      return this.$(".acf-spreadsheet-input");
    },
    initialize: function () {
      this.$input = this.$input();
      let data = [];
      try {
        data = JSON.parse(this.$input.val());
      } catch (e) {}

      let columns = [];
      if (data.length) {
        const columnsCount = Object.keys(data[0]).length;
        for (let i = 0; i < columnsCount; i++) {
          columns.push({
            type: "html",
          });
        }
      }

      const $placeholder = this.$("svg");

      const options = {
        data: data,
        columns: columns,
        tableOverflow: true,
        tableWidth: "100%",
        tableHeight: "100%",
        about: false,
        updateTable: this.saveData.bind(this),
        onload: function () {
          $placeholder.addClass("acf-hidden");
        },
      };

      if (!data.length) {
        options.minDimensions = [3, 3];
      }

      const $control = this.$control();
      const $spreadsheet = $control.jspreadsheet(options);
    },
    saveData: function (instance, cell, col, row, val, label, cellName) {
      this.$input.val(JSON.stringify(instance.jspreadsheet.getJson()));
    },
  });
  acf.registerFieldType(Field);
})(jQuery);

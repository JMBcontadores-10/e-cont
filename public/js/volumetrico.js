function exportReportToExcel(empresa) {
      $('.voluhistori').tableExport({
            type: 'excel',
            fileName: 'E-cont Volumetrico ' + empresa,
            preventInjection: false,
            mso: {
                  styles: ['background-color'],
            }
      });
}


// PDF export using jsPDF only
function exportReportToPdf(empresa) {
      $('.voluhistori').tableExport({
            fileName: 'E-cont Volumetrico ' + empresa,
            type: 'pdf',
            jspdf: {
                  orientation: 'l',
                  format: 'a4',
                  autotable: {
                        styles: {
                              fillColor: 'inherit',
                              textColor: 'inherit',
                        },
                        tableWidth: 'auto',
                  }
            }
      });
}
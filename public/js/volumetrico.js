function exportReportToExcel(empresa) {
      $('.voluhistori').tableExport({
            type: 'excel', fileName: 'E-cont Volumetrico ' + empresa});
}


// PDF export using jsPDF only
function exportReportToPdf(empresa) {
      $('.voluhistori').tableExport({
            fileName: 'E-cont Volumetrico ' + empresa,
            type: 'pdf',
            pdfmake: {
                  enabled: true,
                  docDefinition: { pageOrientation: 'landscape' }
            }
      });
}
const { DocumentProcessorServiceClient } = require('@google-cloud/documentai').v1;
const fs = require('fs');

async function run() {

  const client = new DocumentProcessorServiceClient({
    keyFilename: '../system/agentes/transcooler-480721-69e587eae341.json'
  });

  const projectId = 'transcooler-480721';
  const location = 'us';
  const processorId = '4643122a2495a98a';
  const filePath = '../clientes/sat/constancia2026.pdf';

  const name = `projects/${projectId}/locations/${location}/processors/${processorId}`;

  const file = fs.readFileSync(filePath);
  const encoded = Buffer.from(file).toString('base64');

  const request = {
    name,
    rawDocument: {
      content: encoded,
      mimeType: 'application/pdf'
    }
  };

  const [result] = await client.processDocument(request);
  const text = result.document.text;

  const datos = extraerDatos(text);

  console.log(JSON.stringify(datos));
}

function extraerDatos(text) {

  const rfcMatch = text.match(/[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}/);
  const razonMatch = text.match(/Registro Federal de Contribuyentes\n(.+)/);
  const fechaMatch = text.match(/A (\d{1,2} DE [A-Z]+ DE \d{4})/);

  return {
    rfc: rfcMatch ? rfcMatch[0] : null,
    razon_social: razonMatch ? razonMatch[1].trim() : null,
    fecha: fechaMatch ? fechaMatch[1] : null
  };
}

run();
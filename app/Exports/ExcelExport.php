<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use NumberFormatter;

class ExcelExport implements FromCollection, WithHeadings, WithEvents
{
    private $sender;
    private $shipments;
    private $receivers;
    private $totalQuantity = 0;
    private $grandTotal = 0;
    private $totalBoxes;

   
    public function getNumericQuantity($quantity)
    {
        // Extract numeric part from quantity (remove non-numeric characters)
        $numericQuantity = preg_replace('/[^0-9.]/', '', $quantity);
        
        // Return the numeric value or 0 if the quantity is empty or invalid
        return is_numeric($numericQuantity) ? floatval($numericQuantity) : 0;
    }
    
    // private $totalQuantity;
    // private $grandTotal;
    public function __construct($sender, $shipments, $receivers , $totalBoxes)
    {
        $this->sender = $sender;
        $this->shipments = $shipments;
        $this->receivers = $receivers;
        $this->totalBoxes = $totalBoxes;
        // $this->totalQuantity = $totalQuantity;
        // $this->$grandTotal = $grandTotal;
    }
    public function collection()
    {
        $data = [];
        foreach ($this->sender->boxes as $box) {
            foreach ($box->items as $index => $item) {
                $data[] = [
                    'box_number' => $box->box_number,
                    'sr_no' => $index + 1,
                    'description' => $item->item,
                    'hs_code' => $item->hs_code,
                    'unit_type' => $item->unit_type,
                    'quantity' => $item->quantity,
                    'unit_rate' => number_format($item->unit_rate, 2),
                    'amount' => number_format($item->amount, 2),
                ];
            }
        }
        return collect([]);
    }
    public function map($row): array
    {
        // Update the total quantity and grand total
        // $this->totalQuantity += $row->quantity;  // Assuming `quantity` exists in your model
        // $this->grandTotal += $row->amount;  // Assuming `amount` is already calculated
        return [
            $row->invoice_id,
            $row->customer_name,
            $row->quantity,           // Quantity of items
            $row->unit_price,         // Unit price of items
            $row->amount,             // Total amount (quantity * unit price)
            $row->invoice_date->format('Y-m-d'),
            $row->due_date->format('Y-m-d'),
            $row->status,
        ];
    }
    public function headings(): array
    {
        return [];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $shipment = $this->shipments->first();
                $receiver = $this->receivers->first();
                // Merge and style the header cells
                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(10);
                $sheet->getColumnDimension('C')->setWidth(45);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getStyle("A6:C6")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                            'color' => ['rgb' => '000000'], // Black border color
                        ],
                    ],
                ]);

                $sheet->getStyle("A2:A5")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                            'color' => ['rgb' => '000000'], // Black border color
                        ],
                    ],
                ]);
                $sheet->getStyle("A7:A15")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                            'color' => ['rgb' => '000000'], // Black border color
                        ],
                    ],
                ]);

              
                $sheet->getStyle("D6:G6")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                            'color' => ['rgb' => '000000'], // Black border color
                        ],
                    ],
                ]);
                $sheet->mergeCells('A1:G1');
               
               // Merging cells from D10 to G11 as one large cell

               
                 
                for ($row = 2; $row <= 13; $row++) {
                    $sheet->mergeCells("A{$row}:C{$row}");
                    $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                        'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT, // Align text to the left
                    ],
                        'borders' => [
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                                'color' => ['rgb' => '000000'], // Black border color
                            ],
                        ],
                    ]);
                }
                for ($row = 14; $row <= 15; $row++) {
                    $sheet->mergeCells("A{$row}:C{$row}");
                    $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                                'color' => ['rgb' => '000000'], // Black border color
                            ],
                        ],
                    ]);
                }
                for ($row = 2; $row <= 9; $row++) {
                    $sheet->mergeCells("D{$row}:G{$row}");
                    $sheet->getStyle("D{$row}:G{$row}")->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                                'color' => ['rgb' => '000000'], // Black border color
                            ],
                        ],
                    ]);
                }
                for ($row = 13; $row <= 15; $row++) {
                    $sheet->mergeCells("D{$row}:G{$row}");
                    $sheet->getStyle("D{$row}:G{$row}")->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                                'color' => ['rgb' => '000000'], // Black border color
                            ],
                        ],
                    ]);
                }


                for ($row = 10; $row <= 12; $row++) {
                    $sheet->mergeCells("D{$row}:G{$row}");
                   
                    $sheet->getStyle("D{$row}:G{$row}")->applyFromArray([
                        'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT, // Align text to the left
                    ],
                        'borders' => [
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                                'color' => ['rgb' => '000000'], // Black border color
                            ],
                        ],
                    ]);
                }
               
                $sheet->setCellValue('A1', '  PRIME GURKHA LOGISTICS PVT. LTD.');
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, // Thin border
                            'color' => ['rgb' => '000000'], // Black border color
                        ],
                    ],
                ]);
                // Add static details for sender and consignee
                $sheet->setCellValue('A2', 'COUNTRY OF ORIGIN: NEPAL');
                $sheet->setCellValue('A3', 'INVOICE DATE: ' .  ($shipment ? $shipment->invoice_date : 'N/A'));
                $sheet->setCellValue('A4', 'INVOICE NO: ' .  $this->sender->invoiceId ) ;
             
                $sheet->setCellValue('A6', 'SHIPPER');
                $sheet->setCellValue('A7', strtoupper($this->sender->company_name) ); 
                
                $sheet->setCellValue('A8',  strtoupper($this->sender->senderName));
                $sheet->setCellValue('A9', strtoupper($this->sender->address1) );
                $sheet->setCellValue('A11', 'ADDRESS 2');
                $sheet->setCellValue('A10', strtoupper($this->sender->address2) );
                $sheet->setCellValue('A12', strtoupper($this->sender->address3) );
               
                $sheet->setCellValue('A13', 'EMAIL: ' . strtoupper($this->sender->senderEmail));
                 $sheet->setCellValue('A14', 'PHONE NUMBER: ' .strtoupper ($this->sender->senderPhone));
                $sheet->setCellValue('D2', 'ACTUAL WEIGHT :' .strtoupper ($shipment ? $shipment->actual_weight : 'N/A'));
                $sheet->setCellValue('D3', 'TOTAL BOXES :' .strtoupper ($this->totalBoxes));
                // $sheet->setCellValue('D5', 'Dimension: ' . ($shipment ? $shipment->dimension : 'N/A'));
                $sheet->setCellValue('D6', 'CONSIGNEE');
                $sheet->setCellValue('D7',  strtoupper($receiver ? $receiver->receiver_company_name : 'N/A'));
                $sheet->setCellValue('D8', strtoupper($receiver ? $receiver->receiverName : 'N/A'));
                $sheet->setCellValue('D9', strtoupper($receiver ? $receiver->receiverAddress : 'N/A'));
                $sheet->setCellValue('A11', 'ADDRESS 2');
                $sheet->setCellValue('D10',  strtoupper($receiver ? $receiver->receiverPostalcode : 'N/A'));
                $sheet->setCellValue('D12', strtoupper ($receiver ? $receiver->receiverCountry : 'N/A'));
                $sheet->setCellValue('D13', 'EMAIL: ' .  strtoupper($receiver ? $receiver->receiverEmail : 'N/A'));
                $sheet->setCellValue('D14', 'PHONE NUMBER: ' . strtoupper($receiver ? $receiver->receiverPhone : 'N/A'));
        

            
        
               // Optionally auto-adjust the column width
                
                // Add table headers
                $sheet->setCellValue('A16', 'BOXES');
                $sheet->setCellValue('B16', 'SR NO');
                $sheet->setCellValue('C16', 'DESCRIPTION');
                $sheet->setCellValue('D16', 'HS CODE');
                $sheet->setCellValue('E16', 'QUANTITY');
                $sheet->setCellValue('F16', 'UNIT RATE');
                $sheet->setCellValue('G16', 'AMOUNT (USD)');
                // Apply styles to table headers
                $sheet->getStyle('A16:G16')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                ]);
                // Add table data
                $row = 17;
                foreach ($this->sender->boxes as $box) {
                    $sheet->getStyle("A{$row}:G" . ($row + 1))->applyFromArray([
                        'font' => ['bold' => true],
                 
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
    
                    ]);
                    $sheet->mergeCells("A{$row}:A" . ($row + count($box->items) - 1));
                      $sheet->getStyle("A{$row}:A" . ($row + count($box->items) - 1))->applyFromArray([
                  
                    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Center the text horizontally
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER  // Optionally center vertically
    ], 
                    
                ]);
                    $sheet->setCellValue("A{$row}", $box->box_number);
                    foreach ($box->items as $index => $item) {


                        $sheet->getStyle("B{$row}:G" . ($row ))->applyFromArray([
                            'font' => ['bold' => true],
                     
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
        
                        ]);
                        $sheet->setCellValue("B{$row}", $index + 1);
                        $sheet->setCellValue("C{$row}", $item->item);
                        $sheet->setCellValue("D{$row}", $item->hs_code);
                        $sheet->setCellValue("E{$row}", $item->quantity);
                        $sheet->setCellValue("F{$row}", number_format($item->unit_rate, 2));
                        $sheet->setCellValue("G{$row}", number_format($item->amount, 2));
                        $this->totalQuantity += $this->getNumericQuantity($item->quantity);
                        // Assuming `quantity` exists in your model
                        $this->grandTotal += $item->amount;  // Assuming `amount` is already calculated
                
                        
                        $row++;
                    }
                }
                 $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                 
                // Add total row
                $sheet->setCellValue("D{$row}", 'Total Quantity');
                $sheet->setCellValue("E{$row}", $this->totalQuantity);
                $sheet->setCellValue("F{$row}", 'Grand Total');
                $sheet->setCellValue("G{$row}", $this->grandTotal); 
                 $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                ]);
                

                $row++;

                $sheet->mergeCells("A{$row}:E{$row}"); // Merge the total row across all columns

                // Apply styles to the total row
                $sheet->setCellValue("A{$row}",  strtoupper($formatter->format($this->grandTotal)) . ' ONLY');
          
                $sheet->setCellValue("F{$row}", 'Total'); // Empty cell for AMOUNT  
                $sheet->setCellValue("G{$row}", $this->grandTotal); // Empty cell for AMOUNT

                 $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                ]);

$row++;

                // Apply styles to the total row
                $sheet->mergeCells("A{$row}:C{$row}");
                $sheet->mergeCells("D{$row}:G{$row}");
                $sheet->setCellValue("A{$row}", 'NOTE: ' );
                $sheet->setCellValue("D{$row}", ' SIGNATURE/STAMP' );
                
                 $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                ]);

                $row++;
                $sheet->mergeCells("A{$row}:C{$row }");
                $sheet->mergeCells("D{$row}:G{$row}");
                $sheet->setCellValue("A{$row}", 'This above mention goods are made in Nepalother description is true' );
                $sheet->setCellValue("D{$row}", '' );
                
                 $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                ]);
               
            },
        ];
    }
}
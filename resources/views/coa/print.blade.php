<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Box Example</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="box">
        <!-- Content inside the box -->
        <div class="row">
            <div class="col-lg-12">
                <table>
                    <tr>
                      <th>Characteristic</th>
                      <th>Test Method</th>
                      <th>Unit</th>
                      <th>Standard</th>
                      <th>Sample</th>
                    </tr>
                    <tr>
                      <td>Colour</td>
                      <td>Unprinted</td>
                      <td>-</td>
                      <td>-</td>
                      <td><input type="text" placeholder="Transparent Average (15.2)"></td>
                    </tr>
                    <tr>
                      <td>Thickness</td>
                      <td>-</td>
                      <td>µ</td>
                      <td>15 (±3)</td>
                      <td><input type="text" placeholder="15.2"></td>
                    </tr>
                    <tr>
                      <td rowspan="2">Tensile Strength
                        <br> -MD
                        <br> -TD
                      </td>
                      <td>ASTM D-882</td>
                      <td>N/mm²</td>
                      <td>min 100</td>
                      <td><input type="text" placeholder="143.47"></td>
                    </tr>
                    <tr>
                      <td>ASTM D-882</td>
                      <td>N/mm²</td>
                      <td>min 100</td>
                      <td><input type="text" placeholder="155.53"></td>
                    </tr>
                    <tr>
                      <td rowspan="2">Shrinkage
                        <br> -MD
                        <br> -TD
                      </td>
                      <td>ASTM D-1204</td>
                      <td>%</td>
                      <td>min 60</td>
                      <td><input type="text" placeholder="68"></td>
                    </tr>
                    <tr>
                      <td>ASTM D-1204</td>
                      <td>%</td>
                      <td>min 60</td>
                      <td><input type="text" placeholder="70"></td>
                    </tr>
                    <tr>
                      <td rowspan="2">Elongation
                        <br> -MD
                        <br> -TD
                      </td>
                      <td>ASTM D-882</td>
                      <td>%</td>
                      <td>min 90</td>
                      <td><input type="text" placeholder="116.37"></td>
                    </tr>
                    <tr>
                      <td>ASTM D-882</td>
                      <td>%</td>
                      <td>min 90</td>
                      <td><input type="text" placeholder="120.02"></td>
                    </tr>
                    <tr>
                      <td rowspan="2">COF
                        <br> -Static
                        <br> -Kinetic
                      </td>
                      <td>ASTM D-1894-01</td>
                      <td>-</td>
                      <td>0.1 - 0.4</td>
                      <td><input type="text" placeholder="0.23"></td>
                    </tr>
                    <tr>
                      <td>ASTM D-1894-01</td>
                      <td>-</td>
                      <td>0.1 - 0.4</td>
                      <td><input type="text" placeholder="0.14"></td>
                    </tr>
                  </table>
                
            </div>
        </div>
    </div>
</body>
<style>

.box {
    border: 2px solid black; /* Creates a black border */
    padding: 4px 2px; /* Padding: top and bottom 4px, left and right 2px */
    margin: 4px 2px; /* Margin: top and bottom 4px, left and right 2px */
    width: calc(100% - 4px); /* Adjusts the width to be full page width minus the margins */
    box-sizing: border-box; /* Ensures padding and border are included in the width */
}


</style>
</html>

<?php exit() ?>--by kilua 90.222.10.97
--GG Spam by The Saint. Special thanks to Broland
--YOLO! Spam by The Saint.
HK1 = 37 -- Left Arrow (GG)
HK2 = 39 --	Right Arrow (YOLO!)
function OnWndMsg(msg, keycode )
	if keycode == HK1 and msg == KEY_DOWN then
		SendChat("/all   ")
		SendChat("/all   ######        ######   ")
		SendChat("/all  ##                  ##       ")
		SendChat("/all  ##                  ##       ")
		SendChat("/all  ##   ####     ##   ####")
		SendChat("/all  ##        ##     ##       ##")
		SendChat("/all  ##        ##     ##       ##")
		SendChat("/all   ######        ######  ")
	end
	if keycode == HK2 and msg == KEY_DOWN then
	  SendChat("/all   ")
		SendChat("/all ##    ##   ####    ##           ####      ##")
		SendChat("/all  ##  ##  ##    ##  ##         ##    ##    ##")
		SendChat("/all   ####   ##    ##  ##         ##    ##    ##")
		SendChat("/all     ##      ##    ##  ##         ##    ##    ##")
		SendChat("/all     ##      ##    ##  ##         ##    ##    ")
		SendChat("/all     ##        ####    #####    ####      ##")
		SendChat("/all   ")
	end
end

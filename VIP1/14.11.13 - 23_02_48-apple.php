<?php exit() ?>--by apple 84.107.12.248
down = false
function OnWndMsg(msg, key)
	if msg == WM_LBUTTONDOWN  then
		down = true
	elseif msg == WM_LBUTTONUP then
		down = false
	end
end

function OnDraw()
	if down then
		pos = WorldToScreen(myHero.pos)
		distance = math.floor(GetDistance(myHero.pos, mousePos)/10)
		DrawCircle(myHero.x, myHero.y, myHero.z, distance, 0x000066FF)
		DrawText(""..distance, 16, pos.x, pos.y, 0xFF80FF00)
	end
end
function OnTick()
	if down then
		x = math.random(5,10)
		y = math.random(5,10)
		if mousePos.x < myHero.x then
			x = (-1)*x
		end
		if mousePos.z < myHero.z then
			y = (-1)*y
		end
		Packet('S_MOVE', {x = mousePos.x, y = mousePos.z}):send()
		Packet('S_MOVE', {x = myHero.x + x, y = myHero.z+y}):send()
	end
end
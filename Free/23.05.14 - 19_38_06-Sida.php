<?php exit() ?>--by Sida 81.170.70.121
if myHero.charName ~= "Vayne" then return end
if not VIP_USER then return end

TumblePos = {
["Circle"] = { ["x"] = 11589, ["y"] = 52, ["z"] = 4657 },
["Tumble"] = { ["x"] = 11590.95, ["y"] = 4656.26 },
["Cast"] = { ["x"] = 11334.74, ["y"] = 4517.47 }
}

function OnTick()
	if TumbleOverWall then
		if GetDistance(TumblePos.Circle) <= 30 then
			TumbleOverWall = false
			CastSpell(_Q, TumblePos.Cast.x,  TumblePos.Cast.y)
		else
			if GetDistance(TumblePos.Circle) > 30 then myHero:MoveTo(TumblePos.Tumble.x, TumblePos.Tumble.y) end
		end
	end
end

function OnDraw()
	if GetDistance(TumblePos.Circle) < 125 or GetDistance(TumblePos.Circle, mousePos) < 125 then
		DrawCircle(TumblePos.Circle.x, TumblePos.Circle.y, TumblePos.Circle.z, 100, 0x107458)
	else
		DrawCircle(TumblePos.Circle.x, TumblePos.Circle.y, TumblePos.Circle.z, 100, 0x80FFFF)
	end
end

function RoundNumber(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end

function OnSendPacket(p)
	if p.header == 153 and p.size == 26 and (GetDistance(TumblePos.Circle) < 125 or GetDistance(TumblePos.Circle, mousePos) < 125) then
		p.pos = 1
		P_NetworkID = p:DecodeF()
		P_SpellID = p:Decode1()
		if P_NetworkID == myHero.networkID and P_SpellID == _Q then
			if DontBlockNext then
				DontBlockNext = false
			else
				p:Block()
				DontBlockNext = true
				TumbleOverWall = true
			end
		end
	end

	if p.header == 113 and TumbleOverWall == true then
		p.pos = 1
		P_NetworkID = p:DecodeF()
		p:Decode1()
		P_X = p:DecodeF()
		P_X2 = RoundNumber(P_X, 2)
		P_Y = p:DecodeF()
		P_Y2 = RoundNumber(P_Y, 2)
		if not (P_X2 == TumblePos.Tumble.x and P_Y2 == TumblePos.Tumble.y) then
			p:Block()
			myHero:MoveTo(TumblePos.Tumble.x, TumblePos.Tumble.y)
		end
	end
end
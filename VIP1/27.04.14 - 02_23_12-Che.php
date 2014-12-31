<?php exit() ?>--by Che 91.8.147.231
pings = {}

function OnLoad()
	script = scriptConfig("pingSource", "pingSource") 
	script:addParam("enabled", "Enabled", SCRIPT_PARAM_ONOFF, true) 
	script:addParam("uwot", "What to display", SCRIPT_PARAM_LIST, 1, {"Name", "Champion Name"}) 
end

function OnRecvPacket(p)
	if p.header == 0x3F then
		p.pos = 1
		pings[#pings+1] = {
			unk = p:DecodeF(),
			x = p:DecodeF(),
			y = p:DecodeF(),
			target = p:DecodeF(),
			source = p:DecodeF(),
			type = p:Decode1() - 0xB0,
			tick = GetTickCount()
		}
	end
end

function OnDraw()
	if not script.enabled then return end
	for i=1, #pings do
		if pings[i] ~= nil and pings[i].source ~= 0 and GetTickCount() < (pings[i].tick + 1500) then
			local pos = WorldToScreen(D3DXVECTOR3(pings[i].x, myHero.pos.y, pings[i].y))
			local obj = objManager:GetObjectByNetworkId(pings[i].source)
			local text = (script.uwot == 1 and obj.name or obj.charName)
			DrawText(text, 16, pos.x - (GetTextArea(text, 16).x/2), pos.y, 0xFF80FF00)
		else
			table.remove(pings, i)
		end
	end
end
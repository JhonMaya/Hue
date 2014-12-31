<?php exit() ?>--by pqmailer 217.95.230.206
require 'Prodiction'

local tpQ = ProdictManager.GetInstance():AddProdictionObject(_Q, 1475, 1850, 0, 60, myHero)
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1475, DAMAGE_PHYSICAL)
ts.name = "Varus"
local LastQ

function OnTick()
	ts:update()
	if IsKeyDown(GetKey("Y")) then
		CastSpell(_Q, mousePos.x, mousePos.z)
	end
	if LastQ and os.clock() > LastQ + 1 and ts.target then
		local Pos = tpQ:GetPrediction(ts.target)
			ReleaseQ(Pos)
	end
end

function OnSendPacket(p)
	if p.header == 0xE6 then
		p.pos = 1
		
		local sourceNetworkId = p:DecodeF()
		local spellId = p:Decode1()
		
		if sourceNetworkId == myHero.networkID and spellId == 128 then
			p:Block()
		end
	end
end

function ReleaseQ(pos)
	if pos and pos.x ~= nil and pos.y ~= nil and pos.z ~= nil then
		local p = CLoLPacket(0xE6)
		p:EncodeF(myHero.networkID)
		p:Encode1(128)
		p:EncodeF(pos.x)
		p:EncodeF(pos.y)
		p:EncodeF(pos.z)
		p.dwArg1 = 1
		p.dwArg2 = 0
		SendPacket(p)
	end
end

function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "VarusQ" then
		LastQ = os.clock()
	end
end